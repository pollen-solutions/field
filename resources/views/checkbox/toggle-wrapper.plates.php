<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 */
?>
<?php $this->label('before'); ?>

<?php $this->before(); ?>

    <div class="FieldCheckbox-toggleWrapper">
        <?php echo $this->section('content'); ?>
    </div>

<?php $this->after();

$this->label('after');
