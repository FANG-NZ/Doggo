# Doggo

Somar Technical Test by FANG.F (2021)

## What I have done

*  `app/`: PHP (SilverStripe) code
   * `src/Model/Park.php`: Park data  base object
   * `src/Model/PNCCPark.php`: (Palmerston North City Councils) Park object
   * `src/Model/WCCPark.php`: (Wellington City Councils) Park data object
*  `src/Task/`: PHP (SilverStripe) code
   * `src/Task/FetchPNCCParksTask.php`: Task to import parks from PNCC data
   * `src/Task/FetchWCCParksTask.php`: Task to import parks from WCC data
   * `src/Task/ClearDBTask.php`: Task to clear database, this is only for TESTING & DEVELOPING 
* `src/Admin/`: PHP (SilverStripe) code
   *  `src/Admin/ParkAdmin.php`: The admin panel layout for managing Park dataobject through CMS
   *  `src/Admin/Extensions/ParkGridFieldDetailForm_ItemRequest.php`: The subclass of GridFieldDetailForm_ItemRequest to add 'approve pending image' function into the edit form 
* `src/APIController/`: PHP (SilverStripe) code
   * `src/APIController/ParkController.php`: The API controller to access Park dataobjects
* `app/reactclient/`: Frontend code (Javascript, css, scss)
  * `app/reactclient/js`: - The javascript files to create frontend interface & functions
  * `app/reactclient/js`: Scss stylesheet

## Strategry

### Data Model 

I use the `Park` dataobject as parent class to hold the general infomation and methods, and create each single subclass extends from Park for the specific city (`PNCCPark`, `WCCPark`). This is easier to implement differnet methods and append extra info based on each city, especially adding more cities but we don't know the data format for them.

### API Controller

I just create my own API controller to handle the AJAX request instead of SilverStripe Restful Server, because we can customerise loading data based on our requirement and put more other methods in here, like uploading image in this case.

### Frontend 

At the first I have to say I am beginner for Vue at moment, and I spent couple of hours to try running on my local, but didn't work properly. There could be issue with my local dev, so to complete work as developer, I have to use `React+Redux` to implement whole forntend like you did with Vue.

Actually React is quite similar with Vue. The biggest difference between them is Vue using template to create frontend interface, but React using jsx. And Vue can do double-binding, React only does one-way binding. I can't say which one is better, but SilverStripe CMS is trying to use React instead of jQuery(jquery.entwine), on the other hand Vue works prefect with Laravel.

### Bonus task

First of all I use two has_one replations in Park dataobject to hold images, one is for LIVE another one is for PENDING. Once owner uploads image at the first time, it will be stored into PENDING image, when admin approved, it will be moved into LIVE and PENDING deleted after that.

I create my own `ParkGridFieldDetailForm_ItemRequest` class to complete "APPROVE" function. Firstly we need to add one 'approve' button via `getFormActions` into bottom toolbar, and we also have to define the method to handle 'doApprove' request.

Alos I customerise ParkAdmin to manage Park data more easier. There is one more `Doggo-Model-Park-Pending` into `managed_models`, so we can view only Parks with Pending Image by clicking tab, it is like filter function.

## Setup

### Requirements

- Working SilverStripe development environment (e.g. LAMP stack).
- PHP version 7.2 or greater
- Node version 10.* 
- [Composer](https://getcomposer.org/doc/00-intro.md)

Your web root **must** point to the `public/` folder (not the root of the project). 

### Installation

```
composer install
npm install

```

### Configuration

Create a `.env` file in your project root with the standard SilverStripe setup (we have provided a .env.example) as well as:

- `MAPBOX_TOKEN` set to the token pk.eyJ1Ijoic29tYXItZGVzaWduLXN0dWRpb3MiLCJhIjoiY2s1eWJuc2c4MXA5bzNsazBwYTZ6dnM3MiJ9.nReqnpF0FswusJzh405eWw.

### Compilation

```
npm run dev
```
