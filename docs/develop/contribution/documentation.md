---
title: Documentation
---

# How to Contribute to this documentation

The documentation resides in the [gh-pages branch of Carbon](https://github.com/briannesbitt/Carbon/tree/gh-pages).

*   [Fork Carbon](https://github.com/briannesbitt/Carbon/fork)
*   `git clone`
*   `git checkout gh-pages`
*   `git checkout -b [speaking-new-branch-name]`
*   Modify `.src.html` source files
*   `git commit`
*   `git push`
*   Create a pull request against the **gh-pages** branch


### Generators
Documentations uses few generators. They are inside 'tools' folder.
*   `tools/generate-api.php` : generate API documentation from source code and docblocks. It outputs data into `docs/develop/reference.md`
*   `tools/generate-changelog.php` : generate changelog from git tags and commit messages. It outputs data into `docs/develop/changelog.md`
*   `tools/generate-backers.php` : generate backers and sponsors list from Open Collective API. It outputs data into `docs/develop/contribution/backers.md` and `docs/public/backers.json`


### Writing code examples
When writing code examples in the documentation, php block are automatically rendered. All print and echo statements are captured and displayed. If output is multiline it will be displayed below code, otherwise it will be displayed inline.

For example:
````
```php
$mutable = Carbon::now();
$modifiedMutable = $mutable->add(1, 'day');

var_dump($modifiedMutable === $mutable);
```
````

will be rendered as:

```php
$mutable = Carbon::now();
$modifiedMutable = $mutable->add(1, 'day');

var_dump($modifiedMutable === $mutable);
```

if you want to avoid rendering output, you can use `{no-render}` after the opening php tag like `php{no-render}`

```php{no-render}
$mutable = Carbon::now();
$modifiedMutable = $mutable->add(1, 'day');
```