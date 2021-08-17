<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 * @var Pollen\Field\Drivers\CheckboxCollection\CheckboxChoiceInterface $choice
 */
?>
<li class="FieldCheckboxCollection-choice">
    <?php echo $choice->render(); ?>

    <?php if ($children = $choice->getChildren()) : ?>
        <?php $this->insert('choices', ['choices' => $children]); ?>
    <?php endif; ?>
</li>
