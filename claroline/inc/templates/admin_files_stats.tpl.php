<!-- $Id$ -->

<?php echo $this->dialogBox->render(); ?>

<table style="margin: 5px 0 10px 0; padding: 0;">
  <tr>
    <td>
        <form method="post" action="<?php echo $this->formAction; ?>">
            <input type="hidden" name="cmd" id="cmd" value="run" />
            <input type="hidden" name="viewAs" id="viewAs" value="html" />
            <input type="submit" name="changeProperties" value="<?php echo get_lang('Get HTML statistics'); ?>" />
        </form>
    </td>
    <td>
        <form method="post" action="<?php echo $this->formAction; ?>">
            <input type="hidden" name="cmd" id="cmd" value="run" />
            <input type="hidden" name="viewAs" id="viewAs" value="csv" />
            <input type="submit" name="changeProperties" value="<?php echo get_lang('Get CSV statistics'); ?>" />
        </form>
    </td>
  </tr>
</table>

<?php if (!empty($this->stats)) : ?>
<table class="claroTable emphaseLineemphaseLine">
<thead>
  <tr>
    <th><?php echo get_lang('Course code'); ?></th>
    <th><?php echo get_lang('Course title'); ?></th>
    <th><?php echo get_lang('Lecturer(s)'); ?></th>
    <?php
    foreach ($this->allExtensions as $ext) :
    ?>
       <th colspan="2"><?php echo get_lang($ext); ?></th>
    <?php
    endforeach;
    ?>
  </tr>
  <tr>
    <th> </th>
    <th> </th>
    <th> </th>
    <?php
    foreach ($this->allExtensions as $ext) :
    ?>
       <th><?php echo get_lang('Nb'); ?></th>
       <th><?php echo get_lang('Size'); ?></th>
    <?php
    endforeach;
    ?>
  </tr>
</thead>
<tbody>
  <?php
  foreach ($this->stats as $courseCode => $courseInfos) :
  ?>
     <tr>
        <td style="font-weight: bold;"><?php echo $courseCode; ?></td>
        <td><?php echo $courseInfos['courseTitle']; ?></td>
        <td><?php echo $courseInfos['courseTitulars']; ?></td>
        <?php
        foreach ($courseInfos['courseStats'] as $courseStats) :
        ?>
            <td><?php echo $courseStats['count']; ?></td>
            <td><?php echo format_bytes($courseStats['size']); ?></td>
        <?php
        endforeach;
        ?>
    </tr>
  <?php
  endforeach;
  ?>
</tbody>
</table>
<?php endif; ?>