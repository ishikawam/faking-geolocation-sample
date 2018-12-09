<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\Firefox\FirefoxDriver;

class travel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'travel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seleniumで旅を！';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('start');
        $profile = new FirefoxProfile();

        // geolocationの準備
        $profile->setPreference('geo.prompt.testing', true);
        $profile->setPreference('geo.prompt.testing.allow', true);
        $profile->setPreference('geo.enabled', true);
        $profile->setPreference('geo.provider.use_corelocation', false);
        $profile->setPreference('geo.provider.ms-windows-location', false);

        // localeを日本、languageを日本語、に
        $profile->setPreference('intl.accept_languages', 'ja');

        // 博多駅
        $lat = 33.5897275;
        $lng = 130.4207274;

        // 緯度経度算出
        $profile->setPreference('geo.wifi.uri', sprintf(
                'data:application/json,{"location": {"lat": "%s", "lng": "%s"}, "accuracy": 10.0, "status": "OK"}',
                $lat,
                $lng
            ));

        $caps = DesiredCapabilities::firefox();
        $caps->setCapability(FirefoxDriver::PROFILE, $profile);

        $this->info('open browser');
        $driver = RemoteWebDriver::create('http://firefox:4444/wd/hub', $caps);

        // Googleを開く
        $this->info('open google');
        $driver->get('https://www.google.co.jp/');

        // 検索ボックスが現れるのを待つ
        $driver->wait(10, 500)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('q'))
        );

        // 検索ボックスにキーワードを入力して検索実行
        $this->info('send query');
        $element = $driver->findElement(WebDriverBy::name('q'));
        $element->sendKeys('焼肉');
        $element->submit();

        // ページ遷移を待つ
        $driver->wait(10, 500)->until(
            // ページ遷移したかどうかは #swml-upd = 「正確な現在地を使用」リンクがあるかどうかで判定
            WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('swml-upd'))
        );

        // スクリーンショットを保存: 位置情報反映されず
        $this->info('save screenshot');
        $driver->takeScreenshot(storage_path('yakiniku-hakata.png'));


        /**
         * sample 2 「正確な現在地を使用」を利用する
         */
        $this->comment('sample 2');

        // 「正確な現在地を使用」をクリック
        $this->info('click swml-upd');
        $driver->findElement(WebDriverBy::id('swml-upd'))->click();

        // 現在地更新されるまで待つ
        $driver->wait(10, 500)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('#loc.known_loc'))
        );

        // reload
        $this->info('reload');
        $driver->navigate()->refresh();

        $driver->wait(10, 500)->until(
            // ページ遷移したかどうかは #swml-upd = 「正確な現在地を使用」リンクがあるかどうかで判定
            WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('swml-upd'))
        );

        // スクリーンショットを保存
        $this->info('save screenshot');
        $driver->takeScreenshot(storage_path('yakiniku-hakata-retry.png'));


        /**
         * sample 3 海外での結果を試す
         */
        $this->comment('sample 3');

        // facebook
        $lat = 33.9750789;
        $lng = -118.4482601;

        // ブラウザを閉じる
        $this->info('close browser');
        $driver->close();

        // 緯度経度算出
        $profile->setPreference('geo.wifi.uri', sprintf(
                'data:application/json,{"location": {"lat": "%s", "lng": "%s"}, "accuracy": 10.0, "status": "OK"}',
                $lat,
                $lng
            ));

        $caps->setCapability(FirefoxDriver::PROFILE, $profile);

        $this->info('open browser');
        $driver = RemoteWebDriver::create('http://firefox:4444/wd/hub', $caps);

        // Googleを開く
        $this->info('open google');
        $driver->get('https://www.google.co.jp/');

        // 検索ボックスが現れるのを待つ
        $driver->wait(10, 500)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('q'))
        );

        // 検索ボックスにキーワードを入力して検索実行
        $this->info('send query');
        $element = $driver->findElement(WebDriverBy::name('q'));
        $element->sendKeys('焼肉');
        $element->submit();

        // ページ遷移を待つ
        $driver->wait(10, 500)->until(
            // ページ遷移したかどうかは #swml-upd = 「正確な現在地を使用」リンクがあるかどうかで判定
            WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('swml-upd'))
        );

        // 「正確な現在地を使用」をクリック
        $this->info('click swml-upd');
        $driver->findElement(WebDriverBy::id('swml-upd'))->click();

        // 現在地更新されるまで待つ
        $driver->wait(10, 500)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('#loc.known_loc'))
        );

        // reload
        $this->info('reload');
        $driver->navigate()->refresh();

        $driver->wait(10, 500)->until(
            // ページ遷移したかどうかは #swml-upd = 「正確な現在地を使用」リンクがあるかどうかで判定
            WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('swml-upd'))
        );

        // スクリーンショットを保存
        $this->info('save screenshot');
        $driver->takeScreenshot(storage_path('yakiniku-facebook.png'));

        // ブラウザを閉じる
        $this->info('close browser');
        $driver->close();

        $this->info('end');
    }
}
