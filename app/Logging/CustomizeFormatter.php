<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class CustomizeFormatter
{
    private $logFormat = "";
    private $dateFormat = 'Y-m-d H:i:s.v';

    public function __construct()
    {
        // ログ出力するカラムを設定
        $cols = [
            "%datetime%",
            "%level_name%",
            "%extra.class%::%extra.function%[LINE: %extra.line%]", // クラス名::メソッド名#行
            "%message%",
            "%context%",
        ];

        // タブ区切りでフォーマットを生成
        $this->logFormat = implode("\t", $cols) . PHP_EOL;
    }

    /**
     * 渡されたロガーインスタンスのカスタマイズ
     *
     * @param $monolog
     * @return void
     */
    public function __invoke($monolog)
    {
        // フォーマットを指定
        $formatter = new LineFormatter($this->logFormat, $this->dateFormat, true, true);

        // extraフィールドの追加
        $ip = new IntrospectionProcessor(Logger::DEBUG, ['Illuminate\\']);

        foreach ($monolog->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
            $handler->pushProcessor($ip);

            // extraフィールドにログ出力対象となるセッション情報を設定する
            $handler->pushProcessor(function ($record) {

                // 対象となるセッションキー設定
                // $cols = ["session_value"];
                // foreach ($cols as $col) {

                //     if (session()->has($col)) {
                //         $record['extra'][$col] = session()->get($col);
                //     }
                // }
                return $record;
            });
        }
    }
}