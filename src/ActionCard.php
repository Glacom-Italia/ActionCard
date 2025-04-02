<?php

declare(strict_types=1);

namespace Glacom\ActionCard;

use Laravel\Nova\Card;
use Exception;

class ActionCard extends Card
{
    public array $actions = [];
    public string $label = '';

    public $width = '1/3';
    public $height = 'dynamic';

    public function component(): string
    {
        return 'action-card';
    }

    /**
     * @param Action[] $actions
     * @return $this
     * @throws Exception if $actions contains something that is not an Action
     */
    public function actions(array $actions): static
    {
        foreach ($actions as $action) {
            if ( ! is_a($action, Action::class)) {
                throw new Exception('The provided actions array contains something that is not a '.Action::class);
            }
        }

        $this->actions = $actions;

        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $label = trim($this->label);

        return array_merge([
            'label' => ! empty($label) ? $label : null,
            'actions' => array_map(fn (Action $action) => $action->jsonSerialize(), $this->actions),
        ], parent::jsonSerialize());
    }
}
