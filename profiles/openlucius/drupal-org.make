; Specify the version of Drupal being used.
core = 7.x
; Specify the api version of Drush Make.
api = 2

; Modules
projects[admin_menu][subdir] = contrib
projects[admin_menu][version] = "3.0-rc4"

projects[better_formats][subdir] = contrib
projects[better_formats][version] = "1.0-beta1"

projects[ctools][subdir] = contrib
projects[ctools][version] = "1.7"

projects[calendar][subdir] = contrib
projects[calendar][version] = "3.5"

projects[comment_alter][subdir] = contrib
projects[comment_alter][download][type] = "git"
projects[comment_alter][download][url] = "http://git.drupal.org/project/comment_alter.git"
projects[comment_alter][type] = "module"

projects[context][subdir] = contrib
projects[context][version] = "3.6"

projects[facetapi][subdir] = contrib
projects[facetapi][version] = "1.5"

projects[date][subdir] = contrib
projects[date][version] = "2.8"
projects[date][patch][] = http://www.drupal.org/files/issues/date_option_render_as_regular_field-1467712-123.patch

projects[date_ical][subdir] = contrib
projects[date_ical][version] = "3.3"

projects[diff][subdir] = contrib
projects[diff][version] = "3.2"

projects[dragndrop_upload][subdir] = contrib
projects[dragndrop_upload][version] = "1.0-alpha2"

projects[entity][subdir] = contrib
projects[entity][version] = "1.6"

projects[features][subdir] = contrib
projects[features][version] = "2.2"

projects[fieldblock][subdir] = contrib
projects[fieldblock][version] = "1.3"

projects[heartbeat][subdir] = contrib
projects[heartbeat][version] = "1.1"

projects[itweak_upload][subdir] = contrib
projects[itweak_upload][version] = "3.x-dev"

projects[jquery_update][subdir] = contrib
projects[jquery_update][version] = "2.4"

projects[libraries][subdir] = contrib
projects[libraries][version] = "2.2"

projects[lightbox2][subdir] = contrib
projects[lightbox2][version] = "1.0-beta1"

projects[logintoboggan][subdir] = contrib
projects[logintoboggan][version] = "1.4"

projects[memcache][subdir] = contrib
projects[memcache][version] = "1.0"

projects[multiupload_filefield_widget][subdir] = contrib
projects[multiupload_filefield_widget][version] = "1.13"

projects[references][subdir] = contrib
projects[references][version] = "2.1"

projects[publish_button][subdir] = contrib
projects[publish_button][version] = "1.0"

projects[realname][subdir] = contrib
projects[realname][version] = "1.2"

projects[rules][subdir] = contrib
projects[rules][version] = "2.7"

projects[search_autocomplete][subdir] = contrib
projects[search_autocomplete][version] = "3.3"

projects[search_facetapi][subdir] = contrib
projects[search_facetapi][version] = "1.0-beta2"

projects[strongarm][subdir] = contrib
projects[strongarm][version] = "2.0"

projects[token][subdir] = contrib
projects[token][version] = "1.5"

projects[transliteration][subdir] = contrib
projects[transliteration][version] = "3.2"

projects[views][subdir] = contrib
projects[views][version] = "3.11"

projects[views_autocomplete_filters][subdir] = contrib
projects[views_autocomplete_filters][version] = "1.1"

projects[views_fluid_grid][subdir] = contrib
projects[views_fluid_grid][version] = "3.0"

projects[wysiwyg][subdir] = contrib
projects[wysiwyg][version] = "2.2"
; Does not want to patch.
;projects[wysiwyg][patch][] = http://www.drupal.org/files/wysiwyg-1963766-10.patch

projects[draggableviews][subdir] = contrib
projects[draggableviews][download][type] = "git"
projects[draggableviews][download][url] = "http://git.drupal.org/project/draggableviews.git"
projects[draggableviews][type] = "module"

projects[features_date_formats][subdir] = contrib
projects[features_date_formats][download][type] = "git"
projects[features_date_formats][download][url] = "http://git.drupal.org/sandbox/rballou/1699026.git"
projects[features_date_formats][type] = "module"

projects[imagecache_actions][subdir] = contrib
projects[imagecache_actions][version] = "1.5"

projects[i18n][subdir] = contrib

projects[variable][subdir] = contrib

projects[views_block_filter_block][subdir] = contrib

; Themes
projects[bootstrap][version] = "3.0"
projects[rubik][version] = "4.1"
projects[tao][version] = "3.1"

; Libraries
libraries[backbone][download][type] = "get"
libraries[backbone][download][url] = "https://github.com/jashkenas/backbone/archive/master.zip"
libraries[backbone][directory_name] = "backbone"
libraries[backbone][type] = "library"

libraries[iCalcreator][download][type] = "get"
libraries[iCalcreator][download][url] = "https://github.com/iCalcreator/iCalcreator/archive/master.zip"
libraries[iCalcreator][directory_name] = "iCalcreator"
libraries[iCalcreator][type] = "library"

libraries[tinymce][download][type] = "file"
libraries[tinymce][download][url] = "https://github.com/downloads/tinymce/tinymce/tinymce_3.5.8.zip"
libraries[tinymce][directory_name] = "tinymce"
libraries[tinymce][type] = "library"

libraries[underscore][download][type] = "get"
libraries[underscore][download][url] = "https://github.com/jashkenas/underscore/archive/master.zip"
libraries[underscore][directory_name] = "underscore"
libraries[underscore][type] = "library"
