<?php
/**
 * Created by vscode.
 * User: jamal13647850
 * Date: 08/18/2020
 * Time: 14:35 PM
 */

namespace jamal\wpmstructure;
abstract class ActionBodyDecorator implements ActionBody {
    protected $actionBody;
    public function __construct(ActionBody $actionBody) {
        $this->actionBody = $actionBody;
    }
    abstract public function loadBody();
}