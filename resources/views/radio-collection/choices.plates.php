<?php
/**
 * @var Pollen\Field\FieldTemplateInterface $this
 * @var Pollen\Field\Drivers\RadioCollection\RadioChoiceInterface[] $choices
 */
?>
<ul class="FieldRadioCollection-choices">
    <?php foreach ($choices as $choice) : ?>
        <?php $this->insert('choice', compact('choice')); ?>
    <?php endforeach; ?>
</ul>