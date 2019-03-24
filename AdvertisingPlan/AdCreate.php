<?php
/**
 * 创建广告计划
 * https://ad.toutiao.com/openapi/doc/index.html?id=57
 * User: zhangwenzong
 * Date: 2019/1/29
 * Time: 14:56
 */

namespace AdvertisingPlan;

use core\Exception\InvalidParamException;
use core\Helper\RequestCheckUtil;
use core\Profile\RpcRequest;

class AdCreate extends RpcRequest
{
    /**
     * @var string
     */
    protected $method = 'POST';
    protected $url = '/2/ad/create/';
    protected $content_type = 'application/json';

    /**
     * 广告主ID
     */
    protected $advertiser_id;

    /**
     * 广告组ID
     */
    protected $campaign_id;

    /**
     * 投放范围
     * 默认值: DEFAULT  允许值: "DEFAULT", "UNION"
     */
    protected $delivery_range;

    /**
     * 广告预算类型
     * 允许值: "BUDGET_MODE_DAY", "BUDGET_MODE_TOTAL"
     */
    protected $budget_mode;

    /**
     * 广告预算
     * 广告预算(最低预算100元,单次预算修改幅度不小于100元,日修改预算不超过20次)。
     * 如果是总预算广告，且设了起始日期，将要求您预算设置需要大于或等于[投放天数 * 100]，即满足每日最低预算100的要求。
     * 取值范围: ≥ 0
     */
    protected $budget;

    /**
     * 广告投放起始时间，形式如：2017-01-01 00:00
     */
    protected $start_time;

    /**
     * 广告投放结束时间，形式如：2017-01-01 00:00
     */
    protected $end_time;

    /**
     * 广告出价（如果是CPC、CPM出价方式的计划请填写bid字段，如果是OCPM出价方式的计划请填写cpq_bid字段）
     * 取值范围: ≥ 0
     */
    protected $bid;

    /**
     * 广告出价类型
     */
    protected $pricing;

    /**
     * 广告投放时间类型
     * 允许值: "SCHEDULE_FROM_NOW", "SCHEDULE_START_END"
     */
    protected $schedule_type;

    /**
     * 广告投放时段，默认全时段投放
     * 格式是48*7位字符串，且都是0或1。
     * 也就是以半个小时为最小粒度，周一至周日每天分为48个区段，0为不投放，1为投放，不传、全传0、全传1均代表全时段投放
     */
    protected $schedule_time;

    /**
     * 广告投放速度类型
     * 允许值: "FLOW_CONTROL_MODE_FAST", "FLOW_CONTROL_MODE_SMOOTH", "FLOW_CONTROL_MODE_BALANCE"
     */
    protected $flow_control_mode;

    /**
     * (可选)
     * 应用直达链接
     */
    protected $open_url;

    /**
     * 应用下载方式，当推广目的是应用下载即landing_type=APP时要求传此字段，默认为按下载链接方式。
     * 可选值：DOWNLOAD_URL（下载链接，默认），EXTERNAL_URL（落地页链接）
     */
    protected $download_type;

    /**
     * 广告落地页链接
     * （当推广目的landing_type=LINK 或者landing_type=APP&download_type=EXTERNAL_URL 时必填）
     *  ——对于转化为目标的计划如OCPM、CPA计划进入审核后将不允许更改，即计划下创建创意后不可更改，非转化为目标的计划如CPC、CPM计划可用更改无此限制。
     * 有以下三种情况要求落地页需要是橙子建站落地页：
     * 1 推广目的是“销售线索收集”，客户类型为SMB时，落地页链接必须使用橙子建站；
     * 2 推广目的为“应用推广”，下载方式为“下载链接”时，应用详情页必须使用橙子建站；
     * 3 推广目的为“应用推广”，下载方式为“落地页”时，落地页链接地址必须使用橙子建站。
     */
    protected $external_url;

    /**
     * 广告应用下载链接
     * 当推广目的landing_type=APP&download_type=DOWNLOAD_URL时必填，landing_type=LINK时不填）
     * ——对于转化为目标的计划如OCPM、CPA计划进入审核后将不允许更改，即计划下创建创意后不可更改，非转化为目标的计划如CPC、CPM计划可用更改无此限制
     */
    protected $download_url;

    /**
     * 广告名称
     * 长度为1-100个字符，其中1个中文字符算2位
     */
    protected $name;

    /**
     * (可选)
     * 广告应用下载类型
     * (当campaign的landing_type=APP&download_type=DOWNLOAD_URL时必填，landing_type=LINK或者download_type=EXTERNAL_URL时不填)
     * 允许值: "APP_ANDROID", "APP_IOS"
     */
    protected $app_type;

    /**
     * 广告应用下载包名(应用下载类型，必填)
     */
    protected $package;

    /**
     * 过滤已转化用户类型字段，只有转化为目标时可填
     */
    protected $hide_if_converted;

    /**
     * 过滤已安装，当推广目标为安卓应用下载时可填
     * 0表示不过滤，1表示过滤，默认为不过滤
     * 默认值: 0 允许值: 0, 1
     */
    protected $hide_if_exists;

    /**
     * ocpm广告转化出价
     * （如果是CPC、CPM出价方式的计划请填写bid字段，如果是OCPM出价方式的计划请填写cpq_bid字段）
     * 取值范围: 0-1000
     */
    protected $cpa_bid;

    /**
     * 转化id
     * 对于OCPM出价是必填项，CPC和CPM不是必填项。
     */
    protected $convert_id;

    /**
     * （可选） 受众相关参数
     * 定向人群包列表，内容为人群包id。（新增字段，使用新增字段支持同时选择定向和排除）
     */
    protected $retargeting_tags_include;

    /**
     * （可选） 受众相关参数
     * 排除人群包列表，内容为人群包id。（新增字段，使用新增字段支持同时选择定向和排除）
     */
    protected $retargeting_tags_exclude;

    /**
     * （可选） 受众相关参数
     * 受众性别
     * 允许值: "GENDER_FEMALE", "GENDER_MALE", "NONE"
     */
    protected $gender;

    /**
     * （可选） 受众相关参数
     * 受众年龄区间
     * 允许值: "AGE_BELOW_18", "AGE_BETWEEN_18_23", "AGE_BETWEEN_24_30","AGE_BETWEEN_31_40", "AGE_BETWEEN_41_49", "AGE_ABOVE_50"
     */
    protected $age;

    /**
     * （可选） 受众相关参数
     * 受众最低android版本(当推广应用下载Android时选填,其余情况不填)
     * 允许值: "0.0", "2.0", "2.1", "2.2", "2.3", "3.0", "3.1", "3.2", "4.0","4.1", "4.2", "4.3", "4.4", "4.5", "5.0", "NONE"
     */
    protected $android_osv;

    /**
     * （可选） 受众相关参数
     * 受众最低ios版本(当推广应用下载iOS时选填,其余情况不填)
     * 允许值: "0.0", "4.0", "4.1", "4.2", "4.3", "5.0", "5.1", "6.0", "7.0","7.1", "8.0", "8.1", "8.2", "9.0", "NONE"
     */
    protected $ios_osv;

    /**
     * （可选） 受众相关参数
     * 受众运营商
     * 允许值: "MOBILE", "UNICOM", "TELCOM"
     */
    protected $carrier;

    /**
     * （可选） 受众相关参数
     * 受众网络类型
     * 允许值: "WIFI", "2G", "3G", "4G"
     */
    protected $ac;

    /**
     * （可选） 受众相关参数
     * 受众手机品牌
     * 允许值: "APPLE", "HUAWEI", "XIAOMI", "SAMSUNG", "OPPO",
     * "VIVO","MEIZU", "GIONEE", "COOLPAD", "LENOVO", "LETV",
     * "ZTE","CHINAMOBILE", "HTC", "PEPPER", "NUBIA", "HISENSE",
     * "QIKU", "TCL","SONY", "SMARTISAN", "360", "ONEPLUS", "LG",
     * "MOTO", "NOKIA","GOOGLE"
     */
    protected $device_brand;

    /**
     * （可选） 受众相关参数
     * 受众文章分类
     * 允许值: "ENTERTAINMENT", "SOCIETY", "CARS", "INTERNATIONAL",
     * "HISTORY", "SPORTS", "HEALTH", "MILITARY", "EMOTION", "FASHION",
     * "PARENTING", "FINANCE", "AMUSEMENT", "DIGITAL", "EXPLORE", "TRAVEL",
     * "CONSTELLATION", "STORIES", "TECHNOLOGY", "GOURMET", "CULTURE","HOME",
     * "MOVIE", "PETS", "GAMES", "WEIGHT_LOSING", "FREAK","EDUCATION", "ESTATE",
     * "SCIENCE", "PHOTOGRAPHY", "REGIMEN", "ESSAY","COLLECTION", "ANIMATION",
     * "COMICS", "TIPS", "DESIGN", "LOCAL","LAWS", "GOVERNMENT", "BUSINESS",
     * "WORKPLACE", "RUMOR_CRACKER","GRADUATES"
     */
    protected $article_category;

    /**
     * （可选） 受众相关参数
     * 用户首次激活时间
     * 允许值: "WITH_IN_A_MONTH", "ONE_MONTH_2_THREE_MONTH","THREE_MONTH_EAILIER"
     */
    protected $activate_type;

    /**
     * （可选） 受众相关参数
     * 受众平台
     * (当推广目的landing_type=APP时,不填,且为保证投放效果,平台类型定向PC与移动端互斥)
     * 允许值: "ANDROID", "IOS", "PC"
     */
    protected $platform;

    /**
     * （可选） 受众相关参数
     * 地域定向城市或者区县列表
     * (当传递省份ID时,旗下市县ID可省略不传)
     */
    protected $city;

    /**
     * （可选） 受众相关参数
     * 地域类型
     * 前者为省市，后者为区县。当city有数据时，必填。
     * 允许值: "CITY", "COUNTY", "NONE"
     */
    protected $district;

    /**
     * （可选） 受众相关参数
     * 受众位置类型
     * 当city和district有值时，该字段必填
     */
    protected $location_type;

    /**
     * （可选） 受众相关参数
     * 兴趣分类
     * 如果传空数组 [] 表示不限，如果只传[0]表示系统推荐,如果按兴趣类型传表示自定义
     */
    protected $ad_tag;

    /**
     * （可选） 受众相关参数
     * 兴趣关键词
     * 传入具体的词id，非兴趣词包id，可以通过词包相关接口或者兴趣关键词word2id接口获取词id，一个计划下最多创建1000个关键词。
     */
    protected $interest_tags;

    /**
     * （可选） 受众相关参数
     * APP行为定向
     * 允许值: "CATEGORY", "APP", "NONE"
     */
    protected $app_behavior_target;

    /**
     * （可选） 受众相关参数
     * APP行为定向,分类集合
     */
    protected $app_category;

    /**
     * （可选） 受众相关参数
     * APP行为定向,APP集合
     */
    protected $app_ids;

    /**
     * （可选） 受众相关参数
     * 产品目录ID
     * (ID由查询产品目录接口得到), 当推广目的landing_type=DPA时必填
     */
    protected $product_platform_id;

    /**
     * （可选） 受众相关参数
     * H5地址参数
     * (DPA推广目的特有,在填写的参数后面添加"=urlencode(开放平台提供的h5链接地址）"
     * 其中urlencode(开放平台提供的h5链接地址）替换为商品库中的h5地址encode的结果)
     */
    protected $external_url_params;

    /**
     * （可选） 受众相关参数
     * 直达链接参数
     * ((DPA推广目的特有,在“产品库中提取的scheme地址"后面追加填写的参数)
     */
    protected $open_url_params;

    /**
     * （可选） 受众相关参数
     * 是否自定义商品定向((DPA推广目的特有,1表示自定义)
     */
    protected $dpa_local_audience;

    /**
     * （可选） 受众相关参数
     * 包含人群包
     * ((DPA推广目的特有,格式举例[{"days": 7, "code": 1001},]
     * day可选范围:1, 7, 14, 28, code候选范围由查询行为人群库接口得到)
     */
    protected $include_custom_actions;

    /**
     * （可选） 受众相关参数
     * 排除人群包
     * ((DPA推广目的特有,格式举例[{"days": 7, "code": 1002},]
     * day可选范围:1, 7, 14, 28, code候选范围由查询行为人群库接口得到)
     */
    protected $exclude_custom_actions;

    /**
     * @param mixed $advertiser_id
     * @return $this
     */
    public function setAdvertiserId($advertiser_id)
    {
        $this->params['advertiser_id'] = $advertiser_id;
        $this->advertiser_id = $advertiser_id;
        return $this;
    }

    /**
     * @param mixed $campaign_id
     * @return $this
     */
    public function setCampaignId($campaign_id)
    {
        $this->params['campaign_id'] = $campaign_id;
        $this->campaign_id = $campaign_id;
        return $this;
    }

    /**
     * @param mixed $delivery_range
     * @return $this
     */
    public function setDeliveryRange($delivery_range)
    {
        $this->params['delivery_range'] = $delivery_range;
        $this->delivery_range = $delivery_range;
        return $this;
    }

    /**
     * @param mixed $budget_mode
     * @return $this
     */
    public function setBudgetMode($budget_mode)
    {
        $this->params['budget_mode'] = $budget_mode;
        $this->budget_mode = $budget_mode;
        return $this;
    }

    /**
     * @param mixed $budget
     * @return $this
     */
    public function setBudget($budget)
    {
        $this->params['budget'] = $budget;
        $this->budget = $budget;
        return $this;
    }

    /**
     * @param mixed $start_time
     * @return $this
     */
    public function setStartTime($start_time)
    {
        $this->params['start_time'] = $start_time;
        $this->start_time = $start_time;
        return $this;
    }

    /**
     * @param mixed $end_time
     * @return $this
     */
    public function setEndTime($end_time)
    {
        $this->params['end_time'] = $end_time;
        $this->end_time = $end_time;
        return $this;
    }

    /**
     * @param mixed $bid
     * @return $this
     */
    public function setBid($bid)
    {
        $this->params['bid'] = $bid;
        $this->bid = $bid;
        return $this;
    }

    /**
     * @param mixed $pricing
     * @return $this
     */
    public function setPricing($pricing)
    {
        $this->params['pricing'] = $pricing;
        $this->pricing = $pricing;
        return $this;
    }

    /**
     * @param mixed $schedule_type
     * @return $this
     */
    public function setScheduleType($schedule_type)
    {
        $this->params['schedule_type'] = $schedule_type;
        $this->schedule_type = $schedule_type;
        return $this;
    }

    /**
     * @param mixed $schedule_time
     * @return $this
     */
    public function setScheduleTime($schedule_time)
    {
        $this->params['schedule_time'] = $schedule_time;
        $this->schedule_time = $schedule_time;
        return $this;
    }

    /**
     * @param mixed $flow_control_mode
     * @return $this
     */
    public function setFlowControlMode($flow_control_mode)
    {
        $this->params['flow_control_mode'] = $flow_control_mode;
        $this->flow_control_mode = $flow_control_mode;
        return $this;
    }

    /**
     * @param mixed $open_url
     * @return $this
     */
    public function setOpenUrl($open_url)
    {
        $this->params['open_url'] = $open_url;
        $this->open_url = $open_url;
        return $this;
    }

    /**
     * @param mixed $download_type
     * @return $this
     */
    public function setDownloadType($download_type)
    {
        $this->params['download_type'] = $download_type;
        $this->download_type = $download_type;
        return $this;
    }

    /**
     * @param mixed $external_url
     * @return $this
     */
    public function setExternalUrl($external_url)
    {
        $this->params['external_url'] = $external_url;
        $this->external_url = $external_url;
        return $this;
    }

    /**
     * @param mixed $download_url
     * @return $this
     */
    public function setDownloadUrl($download_url)
    {
        $this->params['download_url'] = $download_url;
        $this->download_url = $download_url;
        return $this;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->params['name'] = $name;
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $app_type
     * @return $this
     */
    public function setAppType($app_type)
    {
        $this->params['app_type'] = $app_type;
        $this->app_type = $app_type;
        return $this;
    }

    /**
     * @param mixed $package
     * @return $this
     */
    public function setPackage($package)
    {
        $this->params['package'] = $package;
        $this->package = $package;
        return $this;
    }

    /**
     * @param mixed $hide_if_converted
     * @return $this
     */
    public function setHideIfConverted($hide_if_converted)
    {
        $this->params['hide_if_converted'] = $hide_if_converted;
        $this->hide_if_converted = $hide_if_converted;
        return $this;
    }

    /**
     * @param mixed $hide_if_exists
     * @return $this
     */
    public function setHideIfExists($hide_if_exists)
    {
        $this->params['hide_if_exists'] = $hide_if_exists;
        $this->hide_if_exists = $hide_if_exists;
        return $this;
    }

    /**
     * @param mixed $cpa_bid
     * @return $this
     */
    public function setCpaBid($cpa_bid)
    {
        $this->params['cpa_bid'] = $cpa_bid;
        $this->cpa_bid = $cpa_bid;
        return $this;
    }

    /**
     * @param mixed $convert_id
     * @return $this
     */
    public function setConvertId($convert_id)
    {
        $this->params['convert_id'] = $convert_id;
        $this->convert_id = $convert_id;
        return $this;
    }

    /**
     * @param mixed $retargeting_tags_include
     * @return $this
     */
    public function setRetargetingTagsInclude($retargeting_tags_include)
    {
        $this->params['retargeting_tags_include'] = $retargeting_tags_include;
        $this->retargeting_tags_include = $retargeting_tags_include;
        return $this;
    }

    /**
     * @param mixed $retargeting_tags_exclude
     * @return $this
     */
    public function setRetargetingTagsExclude($retargeting_tags_exclude)
    {
        $this->params['retargeting_tags_exclude'] = $retargeting_tags_exclude;
        $this->retargeting_tags_exclude = $retargeting_tags_exclude;
        return $this;
    }

    /**
     * @param mixed $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->params['gender'] = $gender;
        $this->gender = $gender;
        return $this;
    }

    /**
     * @param mixed $age
     * @return $this
     */
    public function setAge($age)
    {
        $this->params['age'] = $age;
        $this->age = $age;
        return $this;
    }

    /**
     * @param mixed $android_osv
     * @return $this
     */
    public function setAndroidOsv($android_osv)
    {
        $this->params['android_osv'] = $android_osv;
        $this->android_osv = $android_osv;
        return $this;
    }

    /**
     * @param mixed $ios_osv
     * @return $this
     */
    public function setIosOsv($ios_osv)
    {
        $this->params['ios_osv'] = $ios_osv;
        $this->ios_osv = $ios_osv;
        return $this;
    }

    /**
     * @param mixed $carrier
     * @return $this
     */
    public function setCarrier($carrier)
    {
        $this->params['carrier'] = $carrier;
        $this->carrier = $carrier;
        return $this;
    }

    /**
     * @param mixed $ac
     * @return $this
     */
    public function setAc($ac)
    {
        $this->params['ac'] = $ac;
        $this->ac = $ac;
        return $this;
    }

    /**
     * @param mixed $device_brand
     * @return $this
     */
    public function setDeviceBrand($device_brand)
    {
        $this->params['device_brand'] = $device_brand;
        $this->device_brand = $device_brand;
        return $this;
    }

    /**
     * @param mixed $article_category
     * @return $this
     */
    public function setArticleCategory($article_category)
    {
        $this->params['article_category'] = $article_category;
        $this->article_category = $article_category;
        return $this;
    }

    /**
     * @param mixed $activate_type
     * @return $this
     */
    public function setActivateType($activate_type)
    {
        $this->params['activate_type'] = $activate_type;
        $this->activate_type = $activate_type;
        return $this;
    }

    /**
     * @param mixed $platform
     * @return $this
     */
    public function setPlatform($platform)
    {
        $this->params['platform'] = $platform;
        $this->platform = $platform;
        return $this;
    }

    /**
     * @param mixed $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->params['city'] = $city;
        $this->city = $city;
        return $this;
    }

    /**
     * @param mixed $district
     * @return $this
     */
    public function setDistrict($district)
    {
        $this->params['district'] = $district;
        $this->district = $district;
        return $this;
    }

    /**
     * @param mixed $location_type
     * @return $this
     */
    public function setLocationType($location_type)
    {
        $this->params['location_type'] = $location_type;
        $this->location_type = $location_type;
        return $this;
    }

    /**
     * @param mixed $ad_tag
     * @return $this
     */
    public function setAdTag($ad_tag)
    {
        $this->params['ad_tag'] = $ad_tag;
        $this->ad_tag = $ad_tag;
        return $this;
    }

    /**
     * @param mixed $interest_tags
     * @return $this
     */
    public function setInterestTags($interest_tags)
    {
        $this->params['interest_tags'] = $interest_tags;
        $this->interest_tags = $interest_tags;
        return $this;
    }

    /**
     * @param mixed $app_behavior_target
     * @return $this
     */
    public function setAppBehaviorTarget($app_behavior_target)
    {
        $this->params['app_behavior_target'] = $app_behavior_target;
        $this->app_behavior_target = $app_behavior_target;
        return $this;
    }

    /**
     * @param mixed $app_category
     * @return $this
     */
    public function setAppCategory($app_category)
    {
        $this->params['app_category'] = $app_category;
        $this->app_category = $app_category;
        return $this;
    }

    /**
     * @param mixed $app_ids
     * @return $this
     */
    public function setAppIds($app_ids)
    {
        $this->params['app_ids'] = $app_ids;
        $this->app_ids = $app_ids;
        return $this;
    }

    /**
     * @param mixed $product_platform_id
     * @return $this
     */
    public function setProductPlatformId($product_platform_id)
    {
        $this->params['product_platform_id'] = $product_platform_id;
        $this->product_platform_id = $product_platform_id;
        return $this;
    }

    /**
     * @param mixed $external_url_params
     * @return $this
     */
    public function setExternalUrlParams($external_url_params)
    {
        $this->params['external_url_params'] = $external_url_params;
        $this->external_url_params = $external_url_params;
        return $this;
    }

    /**
     * @param mixed $open_url_params
     * @return $this
     */
    public function setOpenUrlParams($open_url_params)
    {
        $this->params['open_url_params'] = $open_url_params;
        $this->open_url_params = $open_url_params;
        return $this;
    }

    /**
     * @param mixed $dpa_local_audience
     * @return $this
     */
    public function setDpaLocalAudience($dpa_local_audience)
    {
        $this->params['dpa_local_audience'] = $dpa_local_audience;
        $this->dpa_local_audience = $dpa_local_audience;
        return $this;
    }

    /**
     * @param mixed $include_custom_actions
     * @return $this
     */
    public function setIncludeCustomActions($include_custom_actions)
    {
        $this->params['include_custom_actions'] = $include_custom_actions;
        $this->include_custom_actions = $include_custom_actions;
        return $this;
    }

    /**
     * @param mixed $exclude_custom_actions
     * @return $this
     */
    public function setExcludeCustomActions($exclude_custom_actions)
    {
        $this->params['exclude_custom_actions'] = $exclude_custom_actions;
        $this->exclude_custom_actions = $exclude_custom_actions;
        return $this;
    }

    /**
     *
     * @throws InvalidParamException
     */
    public function check()
    {
/*        RequestCheckUtil::checkAllowField($this->schedule_type, ["SCHEDULE_FROM_NOW", "SCHEDULE_START_END"], 'schedule_type');
        RequestCheckUtil::checkAllowField($this->flow_control_mode, [
            "FLOW_CONTROL_MODE_FAST", "FLOW_CONTROL_MODE_SMOOTH", "FLOW_CONTROL_MODE_BALANCE"
        ], 'flow_control_mode');
        RequestCheckUtil::checkAllowField($this->app_type, ["APP_ANDROID", "APP_IOS"], 'flow_control_mode');
        RequestCheckUtil::checkAllowField($this->hide_if_exists, [0, 1], 'hide_if_exists');
        RequestCheckUtil::checkAllowField($this->gender, ["GENDER_FEMALE", "GENDER_MALE", "NONE"], 'gender');
        RequestCheckUtil::checkAllowField($this->age, [
            "AGE_BELOW_18", "AGE_BETWEEN_18_23", "AGE_BETWEEN_24_30", "AGE_BETWEEN_31_40", "AGE_BETWEEN_41_49", "AGE_ABOVE_50"
        ], 'age');
        RequestCheckUtil::checkAllowField($this->carrier, ["MOBILE", "UNICOM", "TELCOM"], 'carrier');
        RequestCheckUtil::checkAllowField($this->ac, ["WIFI", "2G", "3G", "4G"], 'ac');
        RequestCheckUtil::checkAllowField($this->device_brand, [
            "APPLE", "HUAWEI", "XIAOMI", "SAMSUNG", "OPPO", "VIVO", "MEIZU", "GIONEE", "COOLPAD", "LENOVO", "LETV",
            "ZTE", "CHINAMOBILE", "HTC", "PEPPER", "NUBIA", "HISENSE", "QIKU", "TCL", "SONY", "SMARTISAN", "360",
            "ONEPLUS", "LG", "MOTO", "NOKIA", "GOOGLE"
        ], 'device_brand');
        RequestCheckUtil::checkAllowField($this->article_category, [
            "ENTERTAINMENT", "SOCIETY", "CARS", "INTERNATIONAL", "HISTORY",
            "SPORTS", "HEALTH", "MILITARY", "EMOTION", "FASHION", "PARENTING",
            "FINANCE", "AMUSEMENT", "DIGITAL", "EXPLORE", "TRAVEL", "CONSTELLATION",
            "STORIES", "TECHNOLOGY", "GOURMET", "CULTURE", "HOME", "MOVIE", "PETS",
            "GAMES", "WEIGHT_LOSING", "FREAK", "EDUCATION", "ESTATE", "SCIENCE",
            "PHOTOGRAPHY", "REGIMEN", "ESSAY", "COLLECTION", "ANIMATION", "COMICS",
            "TIPS", "DESIGN", "LOCAL", "LAWS", "GOVERNMENT", "BUSINESS", "WORKPLACE",
            "RUMOR_CRACKER", "GRADUATES"
        ], 'article_category');
        RequestCheckUtil::checkAllowField($this->activate_type, [
            "WITH_IN_A_MONTH", "ONE_MONTH_2_THREE_MONTH", "THREE_MONTH_EAILIER"
        ], 'activate_type');
        RequestCheckUtil::checkAllowField($this->platform, ["ANDROID", "IOS", "PC"], 'platform');
        RequestCheckUtil::checkAllowField($this->district, ["CITY", "COUNTY", "NONE"], 'district');
        RequestCheckUtil::checkAllowField($this->app_behavior_target, ["CATEGORY", "APP", "NONE"], 'app_behavior_target');*/
    }


}