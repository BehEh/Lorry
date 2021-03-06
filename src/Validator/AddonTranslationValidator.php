<?php

namespace Lorry\Validator;

class AddonTranslationValidator extends AbstractValidator
{

    /**
     *
     * @param \Lorry\Model\AddonTranslation $entity
     */
    protected function performValidation($entity)
    {
        $this->validateTitle($entity->getTitle());
    }

    protected function validateTitle($title)
    {
        $this->validateStringLength($title, 4, 64, array(
            'tooShort' => gettext('Title too short.'),
            'tooLong' => gettext('Title too long.'),
        ));
        $this->validateStringPattern($title, '/^[\w\s:.]*$/', gettext('Title invalid.'));
    }
}
