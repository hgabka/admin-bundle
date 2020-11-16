<?php

namespace Hgabka\AdminBundle\Helper\AdminPanel;

interface AdminPanelAdaptorInterface
{
    /**
     * @return AdminPanelActionInterface[]
     */
    public function getAdminPanelActions();
}
