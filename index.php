<?php

/*
 * TODO:
 *
 * Rewrite this. Shouldnt be a test file, like its now.
 *
 * */

require "./src/gpac.php";

use Gogs\Lib\Curl\Exception as ApiException;

// API url
define('API_URL', 'https://git.giaever.org/api/v1');

// The token generated at Gogs
define('API_TOKEN', '142efbfd6fbdf147f03d289f8b22a438eaa1b5d1');

// Edit this one to your authorized accounts password to create tokens.
define('USR_PASS', "mypassword");

// A known user (typically a test user) thats not the authorized one...
define('KNOWN_USR', "tester");

// Known word in repo to search for
define("KNOWN_WORD", "dns");

$client =  new Gogs\API\Client(API_URL, API_TOKEN);

try {

    /**
     * TESTING
     */

    $me = $client->user()->load();

    echo "Authorized user: '" . $me->username . "'\n";

    // Load every repo
    $repos = $me->repos()->load(); 

    // Loop through all of them in received order
    echo "\nNormal repo\n";
    foreach($repos->all() as $key => $repo)
        echo sprintf("* %s: %s\n", $key, $repo->name);

    // Loop through repos sorted on created_at date
    echo "\nSorted created\n";
    foreach($repos->sort_by(Gogs\API\Request\Repos::SORT_CREATED)->all() as $key => $repo)
        echo sprintf("* %s: %s - %s\n", $repo->created_at, $key, $repo->name);

    // Loop through repos sorted on created_at date, but ascending order
    echo "\nSorted created, then reversed\n";
    foreach($repos->sort_by(Gogs\API\Request\Repos::SORT_CREATED, true)->all() as $key => $repo)
        echo sprintf("* %s: %s - %s\n", $repo->created_at, $key, $repo->name);

    // Loop from offset 1 (skip fist) then 10 repos (11th repos returned)
    echo "\nSorted Normal, offset 1, limit 10\n";
    foreach($repos->offset(1)->limit(10)->all() as $key => $repo)
        echo sprintf("* %s: %s\n", $key, $repo->name);

    // Ensure repo order is still intact on $repos ... :)
    // Theres returned a copy of "collection" each "sort"
    echo "\nNormal repo\n";
    foreach($repos->all() as $key => $repo)
        echo sprintf("* %s: %s\n", $key, $repo->name);

    // Look for a common search word, but max 10 entries
    echo "\nSearch for in loaded data for '" . KNOWN_WORD . "', limit 10\n";
    foreach($repos->search(array("name" => KNOWN_WORD, "limit" => 3))->all() as $key => $repo)
        echo sprintf("* %s: %s\n", $key, $repo->name);
    

    // Search for the two first letters of known user. 
    // NOTE! Several users may start on these letters,
    // and offset is 1 so another user that matches,
    // may be returned.
    $st = substr(KNOWN_USR, 0, 2);
    echo "\nUsers->search name '" . $st . "', offset 1:\n";
    foreach($client->users()->search(array("name" => $st))->offset(1)->all() as $key => $user)
       echo sprintf("* %s: %s\n", $key, $user->full_name); 

    $user = $client->users()->get(KNOWN_USR);

    // Get public repos
    echo "\nUser '" . $user->username . "' public repos\n";
    foreach($user->repos()->load()->all() as $key => $repo)
        echo sprintf("* %s: %s\n", $key, $repo->name);

    // Get authorized user's public repos; bug!
    echo "\nUser '" . $me->username . "' public(nope, bug in Gogs....) repos \n";
    foreach($client->users()->get($me->username)->repos()->load()->all() as $key => $repo)
        echo sprintf("* %s: %s\n", $key, $repo->name);

    // Get public organizations
    echo "\nUser '" . $user->username . "' public organizations\n";
    foreach($user->organizations()->load()->all() as $key => $org) {
        echo sprintf("* %s: %s\n* Repositories:\n", $key, $org->full_name);

        // Organization repos? Yes sir!
        foreach($org->repos()->load()->all() as $key => $repo)
            echo sprintf("#### %s: %s\n", $key, $repo->name);
    }

    // Get authorized user's repos; BUG here too! :(
    echo "\nUser '" . $me->username . "' public(nope, bug in Gogs....) organizations\n";
    foreach($me->organizations()->load()->all() as $key => $org) {
        echo sprintf("* %s: %s\n", $key, $org->full_name);

        // Organization repos :)
        foreach($org->repos()->load()->all() as $key => $repo)
            echo sprintf("#### %s: %s\n", $key, $repo->name);
    }

    // Creating a test repo under authorized user
    echo "Create data under specified user\n";
    $repo = $repos->create(
        "test-gogs-api-repo-" . $repos->load()->len(),
        "This is test repo #" . $repos->load()->len() . " created with Gogs PHP API Client",
        false,
        true
    );
    echo "Created repo " . $repo->name . "\n";

    // Deleting this repo again.... And possibly others starting
    // with this bogus prefix.
    echo "\nLooking up repos of test-test-test-#\n";
    foreach($repos->search(array("name" => "test-gogs-api-repo-"))->sort_by()->all() as $key => $repo)
        echo sprintf("... and deleting test repo: '%s' %s\n", $repo->name, $repo->delete() ? "true" : "false");


    echo "\nMigrate repo 'gogs-php-api-client.git'\n";
    $mrepo = $repos->create()->migrate("https://git.giaever.org/joachimmg/gogs-php-api-client.git", "gogs-php-api-client-migrate");
    echo "Syncing repository '" . $mrepo->full_name . "': " . ($mrepo->sync() ? "true" : "false") . "\n";
    echo sprintf("Delete migrated repo: %s\n", $mrepo->delete());

    echo "\nMigrate repo (mirror) 'gogs-php-api-client.git'\n";
    $mrepo = $repos->create()->migrate(
        "https://git.giaever.org/joachimmg/gogs-php-api-client.git", 
        "gogs-php-api-client-migrate-mirror",
        null, null,
        true
    );
    echo "Syncing repository '" . $mrepo->full_name . "': " . ($mrepo->sync() ? "true" : "false") . "\n";
    echo sprintf("Delete migrated repo: %s\n", $mrepo->delete());

    // Load all of my organizations.
    $orgs = $me->orgs()->load();

    /***
     * NOW WE STARTS WITH METHODS THAT REQUIRES A TOKEN
     * FOR AN AUTHORIZED USER THAT HAS ADMIN RIGHTS!
     *
     * Read exception carefully if you get one! ;)
     */

    /*
     * THIS IS LEFT OUT OF EVERY TEST, NO METHOD TO DELETE;
     * ...other than manually. And its so boring to do this all
     * the time....
     * Uncomment to test...
    try {
        echo "\nCreate organization\n";
        $org = $orgs->create(
            "test-" . $me->username . "-organization",
            $me->full_name . " Testing Organization"
        );
        echo "Organization '" . $org->username . "' created!";
    } catch (ApiException\NotAuthorizedException $e) {
        throw new ApiException\NotAuthorizedException("Creating organization", $e->getCode(), $e);
    } catch (ApiException\HTTPUnexpectedResponse $e) {
        echo $e->getResponse();
    }
     */

    // Look for a test organization
    // NOTE! Most likely not showing up unless you
    // uncomment stuff above.
    echo "\nLooking up organizations of test-" . $me->username . "\n";
    foreach($orgs->search(array("name" => "test-" . $me->username))->all() as $key => $org)
        echo sprintf("* '%s': %s\n", $key, $org->username);

    // Get users (without loading data!)
    $users = $client->users();

    // Create new user
    $nuser = $users->create(
        KNOWN_USR . "-" . $users->len(),
        KNOWN_USR . $users->len() . "@gogitservice.joke"
    );

    // Delete test users....
    // Note! As this Users object isnt loaded (->load())
    // the Users-Collection only contains 1 user 
    // - the newly create one!
    echo "Delete user '" . $nuser->username . "\n";
    foreach ($users->all() as $key => $user)
        echo sprintf("%s: delete %s\n", $key, $user->delete() ? "true" : "false");

    echo "\n\n\nLOG:\n" . join("\n", $client->get_log());

} catch (ApiException\NotAuthorizedException $e) {
    echo "\n\n\nLOG:\n" . join("\n", $client->get_log());
    die("NOT AUTH: " . $e->getMessage() . "\nResponse: " . $e->getResponse() . "\n" . <<<EOF
    POSSIBLY WHAT YOU WANTED, CAUSE IT SEEMS LIKE YOUR
    AUTHORIZED USER IS TRYIGN TO PROCESS PARTS OF THE
    INDEX THAT ITS NOT AUTHORIZED TO DO, SUCH AS
        * Creating organizations
        * Creating users
        * More? Sure!

EOF
);
} catch (ApiException\HTTPUnexpectedResponse $e) {
    echo "\n\n\nLOG:\n" . join("\n", $client->get_log());
    die($e);
} catch (Exception $e) {
    die($e);
}
?>
