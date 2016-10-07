Images in icons/* directories are provided as alternatives to drupal stock icons in modules/file/icons.

In D7:
To be compatible with existing file module functions, all icons are separate files, named by their corresponding mime name.

In D6:
These were a source for compound images/mime-16.png and images/mime-32.png that are used to show mime icons with CSS sprites technique. 

There is single image generated for each icon size, with all icons stacked vertically with 2x spacing.

There is additional transparent vertical spacing added on top of each compound image.

  mime-16.png   - add 3px

  mime-32.png   - add 4px

That spacing helps align the icons with the text.


