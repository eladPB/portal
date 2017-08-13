<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
    "Home" => APP_URL
);

/*navigation array config

ex:
"dashboard" => array(
	"title" => "Display Title",
	"url" => "http://yoururl.com",
	"url_target" => "_self",
	"icon" => "fa-home",
	"label_htm" => "<span>Add your custom label/badge html here</span>",
	"sub" => array() //contains array of sub items with the same format as the parent
)

*/
$page_nav = array(
    "dashboard" => array(
        "title" => $lang['DASHBOARD'],
        "icon" => "fa-home",
        "sub" => array(
            "analytics" => array(
                "title" => $lang['ANALYTICS_DASHBOARD'],
                "url" => APP_URL . "/"
            ),
            "social" => array(
                "title" => $lang['SOCIAL_WALL'],
                "url" => APP_URL."/dashboard-social.php"
            )
        )
    )
);

if (isset($_SESSION['sensor_enable']) && $_SESSION['sensor_enable'] == 1) {
    if (isset($_SESSION['sensor_type']) && $_SESSION['sensor_type'] == 1) {
        $page_nav["monitoring"] = array(
            "title" => $lang['MONITORING'],
            "icon" => "fa-soundcloud",
            "sub" => array(
                "smaxtec" => array(
                    'title' => $lang['SMAXTEC'],
                    "sub" => array(
                        "events" => array(
                            "title" => $lang['EVENTS'],
                            "url" => APP_URL . "/smaxtec/events.php"
                        ),
                        "animal_activity" => array(
                            "title" => $lang['ANIMAL_ACTIVITY'],
                            "url" => APP_URL . "/smaxtec/animal_activity.php"
                        ),
                        "animal_temperature" => array(
                            "title" => $lang['ANIMAL_TEMPERATURE'],
                            "url" => APP_URL . "/smaxtec/animal_temperature.php"
                        ),

                        "animal_temperature_group" => array(
                            "title" => $lang['ANIMAL_TEMPERATURE_GROUP'],
                            "url" => APP_URL . "/smaxtec/animal_temperature_group.php"
                        ),
                        "sensor_humidity" => array(
                            "title" => $lang['SENSOR_HUMIDITY'],
                            "url" => APP_URL . "/smaxtec/sensor_humidity.php"
                        ),
                        "sensor_temperature" => array(
                            "title" => $lang['SENSOR_TEMPERATURE'],
                            "url" => APP_URL . "/smaxtec/sensor_temperature.php"
                        )
                    )
                )
            )

        );
    };
}
    if (isset($_SESSION['privileges']) && $_SESSION['privileges'] == 2 ) {
        $page_nav["setting"]  = array(
        "title" => $lang['SETTING'],
        "icon" => "fa-cogs",
        "sub" => array(
            "user" => array(
                "title" => $lang['USER'],
                "url" => APP_URL."/Setting/user.php"
            ),
            "users_list" => array(
                "title" => $lang['USERS_LIST'],
                "url" => APP_URL."/Setting/user_list.php"
            ),
            "farm" => array(
                "title" => $lang['FARM'],
                "url" => APP_URL."/Setting/Farm.php"
            ),
            "farm_list" => array(
                "title" => $lang['FARM_LIST'],
                "url" => APP_URL."/Setting/farm_list.php"
            )
        )
    );
    }

    if (isset($_SESSION['data_entry_enable']) && $_SESSION['data_entry_enable'] == 1 ) {
        $page_nav["data_entry"] =array(
            "title" => $lang['DATA_ENTRY'],
            "icon" => " fa-pencil",
            "sub" => array(
                "milk" => array(
                    "title" => $lang['MILK'],
                    "url" => APP_URL . "/data_entry/milk.php"
                ),
                "milk_measurement" => array(
                    "title" => $lang['MILK_MEASUREMENT'],
                    "url" => APP_URL . "/data_entry/milk_measurement.php"
                ),
                "soler_measurement" => array(
                    "title" => $lang['SOLER_MEASUREMENT'],
                    "url" => APP_URL . "/data_entry/soler_measurement.php"
                ),
                "marketing_measurement" => array(
                    "title" => $lang['MARKETING_MEASUREMENT'],
                    "url" => APP_URL . "/data_entry/marketing_measurement.php"
                ),
                "food" => array(
                    "title" => $lang['FOOD'],
                    "url" => APP_URL . "/data_entry/food.php"
                ),
                "herd" => array(
                    "title" => $lang['HERD'],
                    "url" => APP_URL . "/data_entry/herd.php"
                ),
                "group_mng" => array(
                    "title" => $lang['GROUP_MNG'],
                    "sub" => array(
                        "group" => array(
                            "title" => $lang['GROUP'],
                            "url" => APP_URL . "/data_entry/smaxtec_group/group.php"
                        ),
                        "group_list" => array(
                            "title" => $lang['GROUP_LIST'],
                            "url" => APP_URL . "/data_entry/smaxtec_group/group_list.php"
                        ),
                        "group_time" => array(
                            "title" => $lang['GROUP_TIME'],
                            "url" => APP_URL . "/data_entry/smaxtec_group/group_time.php"
                        )
                    )
                )
            )
        );
}

    if (isset($_SESSION['diary_milk_enable']) && $_SESSION['diary_milk_enable'] == 1 ) {
        $page_nav["data_sheets"] = array(
            "title" => $lang['DATA_SHEETS'],
            "icon" => "fa-table");
        if (isset($_SESSION['diary_milk_type']) && $_SESSION['diary_milk_type'] == 1) {
            $page_nav["data_sheets"]["sub"]["tara"] = array(
                "title" => $lang['TARA'],
                'sub' => array(
                    "milk_delivery" => array(
                        "title" => $lang['MILK_DELIVERY'],
                        "url" => APP_URL . "/Tara/milk_delivery.php"
                    ),
                    "milk_quality_report" => array(
                        "title" => $lang['MILK_QUALITY_REPORT'],
                        "url" => APP_URL . "/Tara/milk_quality_report.php"
                    ),
                    "production_vs_cover" => array(
                        "title" => $lang['PRODUCTION_VS_COVER'],
                        "url" => APP_URL . "/Tara/production_vs_cover.php"
                    ),
                    "milk_components" => array(
                        "title" => $lang['MILK_COMPONENTS'],
                        "url" => APP_URL . "/Tara/milk_components.php"
                    ),
                    "my_milk_components" => array(
                        "title" => $lang['MY_MILK_COMPONENTS'],
                        "url" => APP_URL . "/Tara/my_milk_components.php"
                    ),
                    "annual_summary" => array(
                        "title" => $lang['ANNUAL_SUMMARY'],
                        "url" => APP_URL . "/Tara/annual_summary.php"
                    ),
                    "invoice_summary" => array(
                        "title" => $lang['INVOICE_SUMMARY'],
                        "url" => APP_URL . "/Tara/invoice_summary.php"
                    ),
                    "mehadrin_calculation" => array(
                        "title" => $lang['MEHADRIN_CALCULATION'],
                        "url" => APP_URL . "/Tara/mehadrin_calculation.php"
                    ),
                    "monthly_quota_procedure" => array(
                        "title" => $lang['MONTHLY_QUOTA_PROCEDURE'],
                        "url" => APP_URL . "/Tara/monthly_quota_procedure.php"
                    )
                )
            );
        }
        if (isset($_SESSION['diary_milk_type']) && $_SESSION['diary_milk_type'] == 2 ) {
            $page_nav["data_sheets"]["sub"]["strauss"] = array(
                "title" => $lang['STRAUSS'],
                'sub' => array(
                    "manufacturer_invoices" => array(
                        "title" => $lang['MANUFACTURER_INVOICES'],
                        "url" => APP_URL . "/Strauss/manufacturer_invoices.php"
                    ),
                    "manufacturer_profile" => array(
                        "title" => $lang['MANUFACTURER_PROFILE'],
                        "url" => APP_URL . "/Strauss/manufacturer_profile.php"
                    ),
                    "milk_quality_bacteria" => array(
                        "title" => $lang['MILK_QUALITY_BACTERIA'],
                        "url" => APP_URL . "/Strauss/milk_quality_bacteria.php"
                    ),
                    "marketing_vs_cover" => array(
                        "title" => $lang['MARKETING_VS_COVER'],
                        "url" => APP_URL . "/Strauss/marketing_vs_cover.php"
                    ),
                    "consumer_financial_concentration" => array(
                        "title" => $lang['CONSUMER_FINANCIAL_CONCENTRATION'],
                        "url" => APP_URL . "/Strauss/consumer_financial_concentration.php"
                    )
                )
            );
        }

        /* TNUVA */
        if (isset($_SESSION['diary_milk_type']) && $_SESSION['diary_milk_type'] == 3 ) {
            $page_nav["data_sheets"]["sub"]["tnuva"] = array(
                "title" => $lang['TNUVA'],
                'sub' => array(
                    "manufacturer_invoices" => array(
                        "title" => $lang['MANUFACTURER_INVOICES'],
                        "url" => APP_URL . "/Tnuva/manufacturer_invoices.php"
                    ),
                    "manufacturer_profile" => array(
                        "title" => $lang['MANUFACTURER_PROFILE'],
                        "url" => APP_URL . "/Tnuva/manufacturer_profile.php"
                    ),
                    "milk_quality_bacteria" => array(
                        "title" => $lang['MILK_QUALITY_BACTERIA'],
                        "url" => APP_URL . "/Tnuva/milk_quality_bacteria.php"
                    ),
                    "marketing_vs_cover" => array(
                        "title" => $lang['MARKETING_VS_COVER'],
                        "url" => APP_URL . "/Tnuva/marketing_vs_cover.php"
                    ),
                    "consumer_financial_concentration" => array(
                        "title" => $lang['CONSUMER_FINANCIAL_CONCENTRATION'],
                        "url" => APP_URL . "/Tnuva/consumer_financial_concentration.php"
                    )
                )
            );
        }


    };

$page_nav["finance"] =  array(
        "title" => $lang['FINANCE'],
        "icon" => "fa-money",
        "sub" => array(
            "my_finance" => array(
                "title" => $lang['MY_FINANCE'],
                "url" => APP_URL."/my_finance.php"
            )
        )
    );

if (isset($_SESSION['analytics_enable']) && $_SESSION['analytics_enable'] == 1 )
{
    $page_nav["Analytics"] = array(
        "title" => $lang['ANALYTICS'],
        "icon" => "fa-windows",
        /* "url" => "http://54.210.52.142/QvAJAXZfc/opendoc.htm?document=DairyFarm_Analysis%2FControl%20Room.qvw&host=QVS%40win-qha7m6aogva",  */
        "url" => "http://" . $_SESSION['bi_user'] .":".$_SESSION['bi_pass']."@54.210.52.142/QvAJAXZfc/opendoc.htm?document=DairyFarm_Analysis%2FControl%20Room.qvw&host=QVS%40win-qha7m6aogva",
        "url_target"=> "_blank");
}

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>
?>