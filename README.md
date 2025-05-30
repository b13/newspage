# Newspage

This extension provides a new doktype to be used for news that need to have flexible content, e.g. `tt_content` elements.

The new doktype that is registered is `doktype=24`.


## Plugins

This extension registers 3 Plugins:
- list
    - this plugin shows a list of all news, optionally filtered by a value selected in the plugin, and optionally a frontend filter can be displayed
- recent
- teaser

## Filters

This plugin comes with a couple of filters pre-shipped:
- Category: both as a backend filter and front end filter
- Categories: a front end filter that allows multiple categories to be selected
    - this filter combines the selected categories using an **or** operation
- Date: a front end filter that filters by year and month

### Custom Filters

If you want to create your own filters, you can register them in your `ext_localconf.php` with the following call
 ```php
<?php

B13\Newspage\Service\FilterService::registerFilter(
    'Filter Name',                                                              // this name will be used to call the filter internally
    \Vendor\Ext\Filter\ExampleFilter::class,
    'LLL:EXT:vendor/ext/Private/Language/newspage.xlf:filter.name',             // label to use for the plugin and frontend filter
    'EXT:site_tecselect/Configuration/FlexForms/Newspage/Filter/Partner.xml'    // optional flexform definition for a backend filter
);
```

Your class needs to implement the `B13\Newspage\Filter\FilterInterface` and therefore implement the functions `getItems()` and `getQueryConstraint()`.

The function `getItems()` is used to get all possible values for the filter (both for the plugin settings, as well as the frontend filter).

`getQueryContraint()` is used to add the filtering restriction to the query in the `list` plugin. This function can also return `null` if not all required values are set, or you want to filter everything in the front end using JavaScript.

For an example take a look at the two filters provided by this extension.

If you want to enable the backend plugin filter, you should provide a FlexForm definition that selects the data to filter by.
This file then needs to be passed as the fourth argument when registering the filter.

The created field is automatically passed as `settings.prefilters.name` with the name you registered it with.

For an example take a look at `EXT:newspage/Configuration/FlexForms/Filter/Category.xml`

## Assets

At b13 we often use additional page types (doktype) for categories, overview pages, and tags. This extension adds a 
number of assets ready to use for your own custom doktypes:

| Icon Identifier                        | Icon                                                                                            |
|----------------------------------------|-------------------------------------------------------------------------------------------------|
| apps-pagetree-folder-contains-newspage | ![Folder icon](Resources/Public/Icons/apps-pagetree-folder-contains-newspage.svg "Folder icon") |
| apps-pagetree-newspage-page            | ![News Page icon](Resources/Public/Icons/apps-pagetree-newspage-page.svg "News Page icon")      |
| apps-pagetree-newspage-article         | ![Article icon](Resources/Public/Icons/apps-pagetree-newspage-article.svg "Article icon")       |
| apps-pagetree-newspage-category        | ![Category icon](Resources/Public/Icons/apps-pagetree-newspage-category.svg "Category icon")    |
| apps-pagetree-newspage-overview        | ![Overview icon](Resources/Public/Icons/apps-pagetree-newspage-overview.svg "Overview icon")    |
| apps-pagetree-newspage-tag             | ![Tag icon](Resources/Public/Icons/apps-pagetree-newspage-tag.svg "Tag icon")                   |
| mimetypes-newspage-page                | ![Mimetype icon](Resources/Public/Icons/mimetypes-newspage-page.svg "Mimetype icon")            |

## Miscellaneous

This extension provides a "module" type for folder type pages with its own icon, to make sorting news easier. 

## Extensions providing further functionality to newspage

### b13/newspage-sorting

Available at https://github.com/b13/newspage-sorting

This small extension provides a hook for the TYPO3 DataHandler and automatically sorts news pages into folders by years,
months and days if they are created within a folder that has the module type "newspage" selected.

The folder structure is configurable to disable folders by month and day.

Depending on the amount of news in your project, less folders could be more useful.

### b13/newspage-edit-in-layout

Available at https://github.com/b13/newspage-edit-in-layout

This extension add fields of a news directly in the page layout view and allows quick editing of the most important 
news related data without having to enter formengine.

## ToDos

- `tx_newspage_domain_model_category` should be replaced by `sys_category` as there is no real value from creating a new model for a problem that is already solved within TYPO3
- make recent plugin more filterable (use added filters from list ?)
