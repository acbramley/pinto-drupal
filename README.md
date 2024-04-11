# Pinto

```
          .,,.
        ,;;*;;;;,
       .-'``;-');;.
      /'  .-.  /*;;
    .'    \d    \;;               .;;;,
   / o      `    \;    ,__.     ,;*;;;*;,
  \__, _.__,'   \_.-') __)--.;;;;;*;;;;,
   `""`;;;\       /-')_) __)  `\' ';;;;;;
      ;*;;;        -') `)_)  |\ |  ;;;;*;
      ;;;;|        `---`    O | | ;;*;;;
      *;*;\|                 O  / ;;;;;*
     ;;;;;/|    .-------\      / ;*;;;;;
    ;;;*;/ \    |        '.   (`. ;;;*;;;
    ;;;;;'. ;   |          )   \ | ;;;;;;
    ,;*;;;;\/   |.        /   /` | ';;;*;
     ;;;;;;/    |/       /   /__/   ';;;
     '"*"'/     |       /    |      ;*;
          `""""`        `""""`     ;'
```

# Local dev setup for module and library

This is just one way to do it, not the exclusive way of working.

In root composer.json, add to `repositories`:

```json
{
  "repositories": {
    "local-repos": {
      "type": "path",
      "url": "repos/*",
      "options": {
        "symlink": true
      }
    }
  }
}
```

```sh
# From project root.
mkdir repos
cd repos
git clone git@github.com:dpi/pinto.git pinto
git clone git@git.drupal.org:project/pinto.git pinto-drupal
cd ..
composer require 'dpi/pinto:dev-main as 0.0.1'
composer require drupal/pinto
```
