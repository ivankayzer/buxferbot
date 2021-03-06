<?php

namespace App\Http\Controllers;

use App\Context\Context;
use App\Context\DescriptionContext;
use App\Services\Helper;

class Limit extends Action
{
    public $context = DescriptionContext::class;

    public function signature(): array
    {
        return [
            'limit <category>',
            'l <category>',
        ];
    }

    /**
     * @param DescriptionContext $context
     */
    public function handle(Context $context): void
    {
        $budgets = $this->apiService->getBudgets();

        $limit = $budgets->first(function ($limit) use ($context) {
            return Helper::startsWith(strtolower($limit['name']), strtolower($context->getDescription()));
        });

        if (!$limit) {
            $this->bot->reply($this->unclearContextReply());
            return;
        }

        if (!isset($limit['balance'])) {
            $this->bot->reply(sprintf('%s does not have a limit', $limit['name']));
        }

        $this->bot->reply(sprintf('%s: %s', $limit['name'], $limit['balance']));
    }
}