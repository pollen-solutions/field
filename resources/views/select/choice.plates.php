<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 * @var Pollen\Field\Drivers\Select\SelectChoiceInterface $choice
 */
?>
<?php echo $choice->tagOpen(); ?>
    <?php echo $choice->tagBody(); ?>
    <?php foreach ($choice->getChildren() as $child) : ?>
        <?php $this->insert('choice', ['choice' => $child]); ?>
    <?php endforeach; ?>
<?php echo $choice->tagClose(); ?>

