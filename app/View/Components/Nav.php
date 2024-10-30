<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Nav extends Component
{
    public $path = "";

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->path = request()->path();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return function (array $data) {

             if(isset($data['attributes']['href'])) {

                $data['attributes']["link"] = $data['attributes']['href'];

                if ($data['attributes']['href'] == "/".$this->path) {
                    $data['attributes']['active'] = $data['attributes']['class']." text-[#44B156]";
                } else {
                    $data['attributes']['active'] = $data['attributes']['class'];
                }
            }
            return 'components.nav';
        };

        // return view('components.nav');
    }
}
