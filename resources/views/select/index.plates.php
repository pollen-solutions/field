<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 */
?>
<?php if ($this->get('wrapper')) : ?>
    <?php $this->layout('wrapper', $this->all()); ?>
<?php endif; ?>

<?php $this->label('before'); ?>

<?php $this->before(); ?>
    <select <?php echo $this->htmlAttrs(); ?>>
        <?php foreach ($this->get('choices', []) as $choice) : ?>
            <?php $this->insert('choice', compact('choice')); ?>
        <?php endforeach; ?>
    </select>
<?php $this->after();

$this->label('after');
