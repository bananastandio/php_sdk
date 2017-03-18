# Banana Stand PHP SDK
This repo is for the PHP SDK for Banana Stand. See https://www.bananastand.io for more information.

## Installation
Install with composer using `composer require bananastandio/php_sdk` or copy the folder to your
project and include all files.


## Example Code
```php
require_once('vendor/autoload.php');

use Banana\Client;
use Banana\Models\Event;

$client = new Client("pk_9481b6521b11f26e3431c3ab477e834997fb262bc3e75b4280aa414e1533e403", "sk_4373db13318d1e380067d5e8ffa9981384ecc800b6ae033a9fa455b2934f7f3a");
$client->setApiUrl('https://bananastand.ngrok.io/api/v1/');

// Fetch events:
$events = Event::fetch($client);
foreach ($events as $event) {
    echo $event->id . "|" . $event->visitor_id . "\n";
}

// Delete Event:
// $events[0]->delete();

// Find event
$event = Event::find($client, $events[0]->id);
echo "Found: " . $event->id . "|" . $event->visitor_id . "\n";


// Push Product View Event Asynchronously
$html = $client->getProductPageHtml($event->product_id);
echo "Display this HTML for Product ##{$event->product_id} is: \n<!-- BEGIN HTML -->\n{$html}\n<!-- END HTML -->\n";

// Push view event for that product for customer 123
$client->pushViewEvent($event->product_id, null, 123);
// Show the new HTML
$html = $client->getProductPageHtml($event->product_id);
echo "New HTML display for product ##{$event->id} is: \n<!-- BEGIN HTML -->\n{$html}\n<!-- END HTML -->\n";

```

# Need Help?
Post issues in this github and a developer will respond. Email the support team if you prefer to contact us that way.


# Contributing
### How to contribute
To contribute to the repository:

1. Fork the repository.
2. Clone the forked repository locally.
3. Create a branch descriptive of your work. For example "my_new_feature_xyz". 
4. When you're done work, push up that branch to **your own forked repository** (not the main one).
5. Visit https://github.com/bananastandio/php_sdk and you'll see an option to create a pull request from your forked branch to the master. Create a pull request.
6. Fill out the pull request template with everything it asks for and assign the pull request to someone to review.
7. Set the reviewee as yourself and the requested reviewer as whomever you want to review your PR.
8. Once the PR merges into master then it is ready for production and should be treated as such. It will be deployed to staging within minutes.

### Getting your PR approved
A few key things to note:
* PRs must be approved by all requested reviewers before you can merge.
* After you implement changes requested from a reviewer then post back with a :recycle: to say something like `:recyle: Ready for you to look again at it please.`. **Note:** If you do not do this then you PR may not ever get re-reviewed after comments are taken into acocunt.
* If a PR comment starts with a :beer: then it is just a suggestion and preference of the reviewer and the comment is NON-blocking. That is, your PR may still be approved with these comments.
* If a PR comment starts with a :tipping_hand_man: then it is just informative and requires no action. It's like a "FYI"
* All other PR comments probably need to be addressed unless otherwise agreed by the reviewer.
* After a PR has been approved then you are free to merge.
* PR reviews will happen ASAP but generally within 24 hours. 



### Design and Code Standards


#### General Standards
Configure your IDE or code editor with the following:
* Use 4 spaces - NOT tabs.
* Add new line at the end of every file.

#### PHP Styles
Follow the [PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

##### Other Considerations
1. Keep methods and classes small and sweet. [Follow SRP](https://en.wikipedia.org/wiki/Single_responsibility_principle).
2. If you're adding a lot of comments in your code you pobably should consider whether or not that code should be broken up into multiple methods. This is 95% the case.
