# com.saurabhbatra96.civiimport
Import to any API for CiviCRM - Google Summer of Code

Disclaimer - This repo is not being actively maintained and remains in beta.

### The aim
The aim of this project was to have an easy to use extension to import CiviCRM client data from a plethora of file formats (ex. CSV, Excel sheets) and also have support to import directly from Google Sheets. All this, using AngularJS on top to provide a better UX.

### What has been accomplished
While the extension is not in a state that clients can use with no hassle, it currently serves as a prototype which, with some tweaks (to both UI and the back-end PHP code) can be shipped as a "Core" extension along with CiviCRM.

* Users can comfortably import from a CSV file.
* Users can comfortably import from Google Sheets.
* Strict validation and verification of data to be imported.
  * See: https://github.com/civicrm/civicrm-core/pull/8624
* No need for a page refresh to change previously set parameters (thank you Angular!).

### What needs to be done
* One of the main ideas behind this extension was to have a data-source interface which allowed imports from multiple file formats, currently this extension only supports CSV files.
* Google's Developer Console has this annoying (I know it's for security but that doesn't stop it from being a pain in the back) requirement where you have to list out allowed redirect URLs for your app. That means, basically anyone who's planning on using the extension has to manually insert their URL in Developer's Console, generate their own version of "client_secret.json" and paste it into their extension folder. We need to automate this!
