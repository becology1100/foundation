<?php namespace Orchestra\Foundation\Routing\Extension;

use Orchestra\Contracts\Extension\Listener\Viewer as Listener;
use Orchestra\Foundation\Processor\Extension\Viewer as Processor;

class ViewerController extends Controller implements Listener
{
    /**
     * Extensions Controller routing to manage available extensions.
     *
     * @param \Orchestra\Foundation\Processor\Extension\Viewer  $processor
     */
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;

        parent::__construct();
    }

    /**
     * Setup controller filters.
     *
     * @return void
     */
    protected function setupFilters()
    {
        $this->beforeFilter('orchestra.auth');
        $this->beforeFilter('orchestra.manage');
    }

    /**
     * List all available extensions.
     *
     * GET (:orchestra)/extensions
     *
     * @return mixed
     */
    public function index()
    {
        return $this->processor->index($this);
    }

    /**
     * Response for list of extensions viewer.
     *
     * @param  array  $data
     * @return mixed
     */
    public function showExtensions(array $data)
    {
        set_meta('title', trans("orchestra/foundation::title.extensions.list"));

        return view('orchestra/foundation::extensions.index', $data);
    }
}
