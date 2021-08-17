<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 */
?>
<?php $this->label('before'); ?>

<?php $this->before(); ?>

<?php echo $this->section('content'); ?>

<?php $this->after();

$this->label('after');
