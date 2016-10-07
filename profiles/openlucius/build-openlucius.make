; Specify the version of Drupal being used.
core = 7.x
; Specify the api version of Drush Make.
api = 2

; Include the definition for how to build Drupal core directly, including patches:
includes[] = drupal-org-core.make

projects[openlucius][type] = profile
projects[openlucius][version] = 1.0