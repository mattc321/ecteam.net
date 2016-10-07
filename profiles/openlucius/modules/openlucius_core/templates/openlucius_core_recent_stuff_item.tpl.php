<?php
/**
 * @file
 * Displays an item in /recent-stuf
 */
?>
<li class="timeline">
  <a class="activity_item"
     href="<?php print $vars['link']; ?>">
        <span class="timeline-badge views-field-picture"><span
            class="glyphicon glyphicon-user"></span>
          <?php if ($vars['picture']): ?>
            <?php print $vars['picture']; ?>
          <?php endif; ?>
        </span>
        <span class="timeline-panel">
            <span class="timeline-heading">
                <span
                  class="glyphicon glyphicon-<?php print $vars['type_icon']; ?> activity"></span>
                <h5 class="timeline-title">
                  <strong><?php print $vars['username']; ?></strong>
                  <?php print $vars['between_text']; ?>
                  <strong><?php print $vars['title']; ?></strong>
                </h5>
                <p>
                  <?php if (!empty($vars['group_title'])): ?>
                    <small class="text-muted"><i
                        class="glyphicon glyphicon-record"></i>  <?php print $vars['group_title']; ?>
                    </small>
                  <?php endif; ?>
                  <small class="text-muted time-ago"><i
                      class="glyphicon glyphicon-transfer"></i> <?php print $vars['time_ago']; ?>
                  </small>
                  <?php if (isset($vars['type']) && $vars['type'] != 'ol_group' && $vars['type'] != 'ol_file_folder'): ?>
                    <small class="text-muted time-ago"><i
                        class="glyphicon glyphicon-comment"></i> <?php print $vars['num_comments']; ?>
                    </small>
                  <?php endif; ?>
                </p>

              <?php if ($vars['comment_body']): ?>
                <blockquote><?php print $vars['comment_body']; ?></blockquote>
              <?php endif; ?>

            </span>
        </span>
  </a>
</li>
