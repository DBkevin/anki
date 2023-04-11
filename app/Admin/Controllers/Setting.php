<?php

namespace App\Admin\Controllers;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class Setting extends Form
{
   

    public $description = '页面介绍';
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '网站设置';

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        //dump($request->all());
        $name=$request->name;
        $this->updateEnv(["APP_NAME"=>$name]);
        Artisan::call("cache:clear");
        admin_success('Processed successfully.');
        return  back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->text('name',"网站标题")->rules('required');
        $this->email('keyword',"关键字")->rules('email');
        $this->text("description","网站描述");
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
      
        return [
            'name'       => env("APP_NAME"),
            'email'      => 'John.Doe@gmail.com',
            'created_at' => now(),
        ];
    }
    function updateEnv(array $data)
    {
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';
        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
        $contentArray->transform(function ($item) use ($data){

            foreach ($data as $key => $value){
                if(str_contains($item, $key)){
                    return $key . '=' . $value;
                }
            }
            return $item;
        });
        $content = implode("\n", $contentArray->toArray());
        \File::put($envPath, $content);
    }
}
