<?php

namespace Adapters\ThirdParty;

use CustoMood\Bundle\AppBundle\BaseAdapter\BaseAdapterInterface;

class MyTestAdapter implements BaseAdapterInterface
{

    /**
     * Unique ID of this adapter
     * @return string
     */
    public static function getId(): string
    {
        return "kristaps_geikins_mytestadapter";
    }

    /**
     * Display name that will be used in CM (can be null, in which case the ID will be used)
     * @return string
     */
    public static function getDisplayName(): string
    {
        return "My Test Adapter";
    }

    /**
     * Author
     * @return string
     */
    public static function getAuthor(): string
    {
        return "Kristaps Fabiāns Geikins";
    }

    /**
     * Description (can be null)
     * @return string
     */
    public static function getDescription(): string
    {
        return "
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        ";
    }

    /**
     * Author's website (can be null)
     * @return string
     */
    public static function getWebsite(): string
    {
        return "mailto:kristaps.geikins@outlook.com";
    }

    /**
     * Version
     * @return float
     */
    public static function getVersion(): float
    {
        return 1.0;
    }

    /**
     * If needed, define any settings that your adapter needs here
     * A single array entry should be structured like this:
     * [
     *      'key' => 'my_first_setting', // Key/Name of the setting. This should be unique within your settings
     *      'value' => '0', // Default value
     *      'display_name' => 'My First Setting',
     *      'type' => 2, // 0 = string, 1 = integer, 2 = boolean
     *      'required' => false, // True, if user has to fill this out
     *      'order' => 1, // Order of this setting within your other settings
     * ]
     * @return array
     */
    public static function getSettingsSchema(): array
    {
        return [
            [
                'key' => 'my_test_string',
                'value' => 'Hello!',
                'display_name' => 'My Test String',
                'type' => 0,
                'required' => true,
                'order' => 2
            ],
            [
                'key' => 'my_test_bool',
                'value' => '1',
                'display_name' => 'My Test Bool',
                'type' => 2,
                'required' => false,
                'order' => 1
            ],
            [
                'key' => 'my_test_int',
                'value' => '1337',
                'display_name' => 'My Test Integer',
                'type' => 1,
                'required' => false,
                'order' => 0
            ]
        ];
    }

    protected $projectId;

    protected $settings;

    /**
     * This will get called on load and will be filled with the projectId and also values to the settings defined in getSettingsSchema()
     * @param $projectId string
     * @param $settingValues array
     * @return mixed
     */
    public function load($projectId, $settingValues)
    {
        $this->projectId = $projectId;
        $this->settings = $settingValues;
    }


    public function getMood($dateFrom, $dateTo)
    {
        // TEST ADAPTER: Going to generate test data
        $result = [];

        if ($dateFrom != null && $dateTo != null && $dateFrom < $dateTo) {
            $interval = floor(($dateFrom->diff($dateTo)->days / count($this->getText())) * 1.0);

            $date = \DateTimeImmutable::createFromMutable($dateFrom);
            $counter = 1;
            foreach ($this->getText() as $text) {
                $result[] = [
                    'date' => $date->add(new \DateInterval('P' . $interval * $counter . 'D'))->getTimestamp(),
                    'text' => $text
                ];

                $counter++;
            }
        }

        return $result;
    }

    protected function getText()
    {
        return [
            "Hello, thanks, we're very happy with what you've done!",
            "Very disappointed!",
            "We did like what you did, but the result leaves us very confused.",
            "Good job, but can this be done quicker next time?",
            "When is this going to be done??? :(",
            "Thanks, great work!",
            "What was wrong with the previous version?",
            "My bad...this was actually done well, good job!",
            "Still this hasn't been pushed to live...Have you seen my previous comment?",
            "We're very happy with this, wow!!!"
        ];
    }
}