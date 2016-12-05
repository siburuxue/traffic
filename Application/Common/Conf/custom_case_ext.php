<?php
return array(
	'case_ext_base' => array( //案件录入--交通事故信息采集补录--基本信息
		'place_position' => array( //道路横断面位置
			'01' => '机动车道',
			'02' => '非机动车道',
			'03' => '机非混合车道',
			'04' => '人行道',
			'05' => '人行横道',
			'06' => '紧急停车带',
			'99' => '其他',
		),
		'scene' => array( //现场形态
			'01' => '原始',
			'02' => '变动',
			'03' => '驾车逃逸',
			'04' => '弃车逃逸',
			'05' => '无现场',
			'06' => '二次现场',
			'07' => '伪造现场',
		),
		'danger_result' => array( //运载危险品事故后果
			'01' => '爆炸',
			'02' => '气体泄漏',
			'03' => '液体泄漏',
			'04' => '辐射泄漏',
			'05' => '燃烧',
			'06' => '无后果',
			'99' => '其他',
		),
		'weather' => array( //天气
			'01' => '晴',
			'02' => '阴',
			'03' => '小到中雨',
			'04' => '大到暴雨',
			'05' => '小到中雪',
			'06' => '大到暴雪',
			'07' => '雨夹雪',
			'08' => '霾',
			'09' => '雾',
			'10' => '大雾',
			'11' => '团雾',
			'12' => '强风',
			'13' => '强横风',
			'14' => '台风',
			'15' => '沙尘',
			'16' => '冰雹',
			'17' => '霜冻',
			'18' => '冻雨',
			'19' => '其他',
		),
		'visibility' => array( //能见度
			1 => '50m以内',
			2 => '50-100m',
			3 => '100-200m',
			4 => '200以上',
		),
		'is_detection_escape' => array( //逃逸事故是否侦破
			1 => '是',
			2 => '否',
		),
		'road_info' => array( //路面状况
			'01' => '路面完好',
			'02' => '施工',
			'03' => '壅包',
			'04' => '波浪',
			'05' => '塌陷',
			'06' => '坑槽',
			'07' => '开裂',
			'08' => '车辙',
			'09' => '泛油',
			'10' => '翻浆',
			'11' => '拱起',
			'19' => '其他',
		),
		'road_surface_info' => array( //路表情况
			'01' => '干燥',
			'02' => '潮湿',
			'03' => '积水',
			'04' => '漫水',
			'05' => '积雪',
			'06' => '结冰',
			'07' => '局部冰冻',
			'08' => '泥泞',
			'09' => '油污',
			'10' => '粘土',
			'11' => '落叶',
			'12' => '路障',
			'19' => '其他',
		),
		'traffic_control_type' => array( //交通控制方式
			'01' => '无控制',
			'02' => '民警指挥',
			'03' => '信号灯',
			'04' => '标志',
			'05' => '标线',
			'06' => '其他',
		),
		'illumination' => array( //照明条件
			1 => '白天',
			2 => '夜间有路灯照明',
			3 => '夜间无路灯照明',
			4 => '黎明',
			5 => '黄昏',
		),
		'initial_reason_type' => array( //事故初查原因类型
			0 => array('name' => '违法', 'value' => array('8001' => '未设置道路安全设施', '8002' => '安全设施损坏、灭失', '8003' => '道路缺陷', '8099' => '其他道路原因')),
			1 => array('name' => '非违法过错', 'value' => array('9001' => '制动不当', '9002' => '转向不当', '9003' => '油门控制不当', '9099' => '其他操作不当')),
			2 => array('name' => '意外', 'value' => array('9101' => '自然灾害', '9102' => '机械故障', '9103' => '爆胎', '9199' => '其他意外')),
			3 => array('name' => '其他', 'value' => array('9999' => '其他')),
		),
		'accident_type' => array( //事故形态类型
			0 => array('name' => '车辆间事故', 'value' => array('11' => '碰撞运动车辆', '12' => '碰撞静止车辆', '19' => '其他车辆间事故')),
			1 => array('name' => '车辆与行人', 'value' => array('21' => '刮撞行人', '22' => '碾压行人', '23' => '碰撞后碾压行人', '29' => '其他车辆与行人事故')),
			2 => array('name' => '单车事故', 'value' => array('31' => '侧翻', '32' => '翻滚', '33' => '坠车', '34' => '失火', '35' => '撞固定物', '36' => '撞非固定物', '37' => '自身折叠', '38' => '乘员跌落或抛出', '39' => '其他单车事故')),
		),
		'angle' => array( //车辆间事故碰撞角度
			'10' => '追尾碰撞',
			'20' => '正面碰撞',
			'31' => '侧面碰撞(同向)',
			'32' => '侧面碰撞(对向)',
			'33' => '侧面碰撞(直角)',
			'39' => '侧面碰撞(角度不确定)',
			'41' => '同向刮擦',
			'42' => '对向刮擦',
			'90' => '其他角度碰撞',
		),
		'accident_object_des_fixed' => array( //车辆事故碰撞对象---固定物
			'11' => '中央隔离设施',
			'12' => '同向护栏',
			'13' => '对向护栏',
			'14' => '交通标示支撑物',
			'15' => '缓冲物',
			'16' => '直立的杆或路灯柱',
			'17' => '树木',
			'18' => '桥墩',
			'19' => '隧道口挡墙',
			'20' => '建筑物',
			'21' => '山体',
		),
		'accident_object_des_non_fixed' => array( //车辆事故碰撞对象---非固定物
			'31' => '动物',
			'32' => '作业/维修设备',
		),
		'accident_object_des_other' => array( //车辆事故碰撞对象---其他
			'99' => '其他',
		),
	),

	'case_ext_car' => array( //案件录入--交通事故信息采集补录--车辆信息
		'car_type' => array( //车辆种类
			1 => '机动车',
			2 => '非机动车',
		),
		'car_no_type' => array( //号牌种类
			'01' => '大型汽车号牌',
			'02' => '小型汽车号牌',
			'03' => '使馆汽车号牌',
			'04' => '领馆汽车号牌',
			'05' => '境外汽车号牌',
			'06' => '外籍汽车号牌',
			'07' => '普通摩托车号牌',
			'08' => '轻便摩托车号牌',
			'09' => '使馆摩托车号牌',
			'10' => '领馆摩托车号牌',
			'11' => '境外摩托车号牌',
			'12' => '外籍摩托车号牌',
			'13' => '低速车号牌',
			'14' => '拖拉机号牌',
			'15' => '挂车号牌',
			'16' => '教练汽车号牌',
			'17' => '教练摩托车号牌',
			'18' => '实验汽车号牌',
			'19' => '实验摩托车号牌',
			'20' => '临时入境汽车号牌',
			'21' => '临时入境摩托车号牌',
			'22' => '临时行驶车号牌',
			'23' => '警用汽车号牌',
			'24' => '警用摩托车号牌',
			'25' => '原农机号牌',
			'26' => '香港入境车号牌',
			'27' => '澳门入境车号牌',
			'31' => '武警号牌',
			'41' => '无号牌',
			'42' => '假号牌',
			'43' => '挪用号牌',
			'99' => '其他号牌',
		),
		'travel_status' => array( //车辆行驶状态
			'01' => '起步',
			'02' => '直行加速',
			'03' => '直行减速',
			'04' => '直行匀速',
			'05' => '先减速后加速',
			'06' => '先加速后减速',
			'07' => '超车',
			'08' => '变道向左',
			'09' => '变道向右',
			'10' => '左转弯',
			'11' => '右转弯',
			'12' => '掉头',
			'13' => '倒车',
			'14' => '横穿',
			'15' => '蛇形',
			'16' => '躲避障碍物',
			'17' => '急停车',
			'18' => '停车',
			'19' => '静止',
			'29' => '其他',
		),
		'collision_position' => array( //车辆碰撞方位
			'01' => '1点钟方向',
			'02' => '2点钟方向',
			'03' => '3点钟方向',
			'04' => '4点钟方向',
			'05' => '5点钟方向',
			'06' => '6点钟方向',
			'07' => '7点钟方向',
			'08' => '8点钟方向',
			'09' => '9点钟方向',
			'10' => '10点钟方向',
			'11' => '11点钟方向',
			'12' => '12点钟方向',
			'13' => '顶部',
			'14' => '底部',
			'15' => '方位不明',
			'16' => '无碰撞',
		),
		'character' => array( //在碰撞中角色
			1 => '碰撞',
			2 => '被撞',
			3 => '两者都有',
			4 => '不明',
		),
		'collision_status' => array( //碰撞后车辆形态
			'01' => '停止',
			'02' => '水平滑动',
			'03' => '方向偏移',
			'04' => '翻车',
			'05' => '坠车',
			'06' => '失火',
			'07' => '爆炸',
			'08' => '撞其他机动车辆',
			'09' => '撞非机动车或行人',
			'10' => '撞固定物',
			'11' => '撞非固定物',
			'12' => '自身摺叠',
			'19' => '其他',
		),
		'deform_position' => array( //车辆受损情况车辆变形按部位区区分
			'10' => '无明显变化',
			'11' => '车身前部',
			'12' => '左侧部',
			'13' => '右侧部',
			'14' => '尾部',
			'15' => '顶部',
			'16' => '底盘',
			'17' => '座椅',
			'18' => '多部位',
			'19' => '车身解体',
			'29' => '其他',
		),
		'deform_features' => array( //车辆受损情况车辆变形按部功能区分
			'30' => '无明显变化',
			'31' => '驾驶区',
			'32' => '副驾驶区',
			'33' => '乘员区',
			'34' => '载货区',
			'35' => '发动机仓区',
			'36' => '多部位',
			'49' => '其他',
		),
		'deform_other' => array( //车辆受损情况其他损伤
			'50' => '无',
			'51' => '爆胎',
			'52' => '浸水',
			'53' => '烧毁',
			'54' => '炸毁',
			'59' => '其他',
		),
		'damage_degree' => array( //车辆损坏程度
			1 => '报废',
			2 => '报严重损害',
			3 => '一般损坏',
			4 => '轻微损坏',
			5 => '无损坏',
		),
		'avoid_measures' => array( //车辆避让措施
			1 => '制动',
			2 => '避让',
			3 => '制动加避让',
			4 => '其他避让措施',
			5 => '未采取措施',
			6 => '不明',
		),
		'gears_type' => array( //手动挡自动挡类型
			1 => array('name' => '手动挡', 'value' => array('10' => '空挡', '11' => '1挡', '12' => '2挡', '13' => '3挡', '14' => '4挡', '15' => '5挡', '16' => '6挡', '17' => '7挡', '18' => '18挡', '19' => '19挡', '20' => 'R挡', '29' => '不明')),
			2 => array('name' => '自动挡', 'value' => array('31' => 'D挡', '32' => 'N挡', '33' => '1挡', '34' => '2挡', '35' => 'P挡', '36' => 'R挡', '37' => 'S挡', '39' => '不明')),
		),
		'steer_light' => array( //车辆转向灯状态
			1 => '未打开',
			2 => '左转灯开',
			3 => '右转灯开',
			4 => '双闪灯开',
			9 => '不明',
		),
		'light_state' => array( //车辆照明灯状态
			1 => '未打开',
			2 => '位置灯开',
			3 => '远光灯开',
			4 => '近光灯开',
			5 => '雾灯开',
			6 => '车内照明灯开',
			9 => '不明',
		),
		'airbag_state' => array( //安全气囊状态
			1 => '无气囊',
			2 => '未碰撞自展开',
			3 => '碰撞后正面气囊展开',
			4 => '碰撞后侧面气囊展开',
			5 => '碰撞后正面、侧面气囊均展开',
			6 => '碰撞后气囊未展开',
			9 => '不明',
		),
		'safety' => array( //车辆安全装置配备情况
			1 => '无',
			2 => 'ABS',
			3 => 'ESC',
			4 => '缓速器',
			5 => '汽车行驶记录仪',
			6 => 'GPS',
			7 => '胎压报警器',
			8 => '应急锤',
			9 => '其他',
		),
		'safety_failure' => array( //车辆安全装置失效情况
			1 => '无',
			2 => 'ABS',
			3 => 'ESC',
			4 => '缓速器',
			5 => '汽车行驶记录仪',
			6 => 'GPS',
			7 => '胎压报警器',
			8 => '应急锤',
			9 => '其他',
		),
		'vehicle_shape_type' => array( //车辆形状-类型
			1 => array('name' => '小/微型客车', 'value' => array('11' => '单厢', '12' => '两厢', '13' => '三厢')),
			2 => array('name' => '大/中型客车', 'value' => array('21' => '平头', '22' => '长头')),
			3 => array('name' => '货车', 'value' => array('31' => '平头', '32' => '长头')),
			4 => array('name' => '拖拉机', 'value' => array('41' => '方向盘式', '42' => '手扶式')),
		),
		'insurance_type' => array( //保险种类
			1 => '交强险',
			2 => '商业第三者险',
			3 => '承运人责任险',
			4 => '车上责任险',
			5 => '其他险种',
			7 => '未投保',
		),
		'legal_status' => array( //车辆合法状况
			1 => '正常',
			2 => '未按期检验',
			3 => '非法改拼装',
			4 => '非法生产',
			5 => '报废',
			9 => '其他',
		),
		'safty_status' => array( //车辆安全状况
			1 => '正常',
			2 => '制动失效',
			3 => '制动不良',
			4 => '转向失效',
			5 => '照明与信号装置失效',
			6 => '爆胎',
			7 => '轮胎磨损/割伤',
			8 => '渗漏油/液/气',
			9 => '其他',
		),
		'use_property_type' => array( //车辆形状-类型
			1 => array('name' => '营运', 'value' => array('11' => '公路客运', '12' => '公交客运', '13' => '出租客运', '14' => '旅游客运', '15' => '货运', '16' => '危险品运输', '17' => '租赁', '18' => '教练', '19' => '其他运营')),
			2 => array('name' => '非营运', 'value' => array('21' => '警用', '22' => '消防', '23' => '工程救险车', '24' => '党政机关用车', '25' => '企事业单位用车', '26' => '施工作业车', '27' => '校车', '28' => '私用', '29' => '其他')),
		),
		'dangerous' => array( //运载危险品种类
			1 => '易燃易爆',
			2 => '剧毒化学品',
			3 => '毒性物质',
			4 => '放射性物质',
			5 => '腐蚀性物质',
			6 => '感染性物质',
			9 => '其他',
		),
		'is_transport' => array( //有无危险品运输许可证
			1 => '有',
			2 => '无',
		),
		'company_level' => array( //客运企业等级
			1 => '一级',
			2 => '二级',
			3 => '三级',
			4 => '四级',
			5 => '五级',
			6 => '无',
		),
		'enterprise_nature' => array( //企业性质
			1 => '国有',
			2 => '集体',
			3 => '私营',
			4 => '其他',
		),
		'manage_nature' => array( //线路经营性质
			'11' => '一类客运班线',
			'12' => '二类客运班线',
			'13' => '三类客运班线',
			'14' => '四类客运班线',
			'15' => '包车客运',
			'16' => '旅游客运',
		),
		'is_inbusiness' => array( //是否进站经营
			1 => '进站',
			2 => '未进站',
			3 => '不明',
		),
		'security_check' => array( //车辆出站安全检查
			1 => '经过检查',
			2 => '检查把关不严',
			3 => '未检查',
			4 => '不明',
		),
		'maintenance' => array( //车辆二级维护
			1 => '正常维护',
			2 => '未按期维护',
			3 => '未有效维护',
			4 => '其他',
			4 => '不明',
		),

	),

	'case_ext_road' => array( //案件录入--交通事故信息采集补录--道路信息
		'road_type' => array( //道路类型
			1 => array('name' => '公路', 'value' => array('10' => '高速', '11' => '一级', '12' => '二级', '13' => '三级', '14' => '四级', '19' => '等外')),
			2 => array('name' => '城市道路', 'value' => array('21' => '城市快速路', '22' => '一般城市道路', '25' => '单位小区自建路', '26' => '公共停车场', '27' => '公共广场', '29' => '其他路')),
		),
		'highway_level' => array( //公路行政等级 1-国道 2-省道 3-县道 4-乡道 9-其他

			1 => '国道',
			2 => '省道',
			3 => '县道',
			4 => '乡道',
			9 => '其他',

		),
		'terrain' => array( //地形 1-平原 2-丘陵 3-山区
			1 => '平原',
			2 => '丘陵',
			3 => '山区',
		),
		'intersection_type' => array( //道路类型
			1 => array('name' => '路口', 'value' => array('11' => '三枝分叉口', '12' => '四枝分叉口', '13' => '多枝分叉口', '14' => '环形交叉口', '15' => '匝道口', '16' => '铁路平交道口', '19' => '其他路口')),
			2 => array('name' => '路段', 'value' => array('21' => '普通路段', '22' => '高架路段', '23' => '变窄路段', '24' => '窄路', '25' => '桥梁', '26' => '隧道', '27' => '路段进出处', '28' => '临水路段', '29' => '临崖路段', '30' => '其他路侧险要路段', '31' => '匝道', '39' => '其他特殊路段')),
		),
		'plane_alignment' => array( //平面线形 1-直线段 2-左一般弯 3-左急弯 4-右一般弯 5-右急弯 6-连续弯
			1 => '直线段',
			2 => '左一般弯',
			3 => '左急弯',
			4 => '右一般弯',
			5 => '右急弯',
			6 => '连续弯',
		),
		'longitudinal_section' => array( //纵断面线形 1-无坡度 2-上一般坡 3-上陡坡 4-下一般坡 5-下陡坡 6-连续上坡 7-连续下坡
			1 => '无坡度',
			2 => '上一般坡',
			3 => '上陡坡',
			4 => '下一般坡',
			5 => '下陡坡',
			6 => '连续上坡',
			7 => '连续下坡',
		),
		'special' => array( //特殊 1-无 2-直曲结合部 3-曲直结合部 4-坡底 5-坡顶
			1 => '无',
			2 => '直曲结合部',
			3 => '曲直结合部',
			4 => '坡底',
			5 => '坡顶',
		),
		'physical_isolation' => array( //道路物理隔离 1-无隔离  2-中央隔离  3-机非隔离  4-中央隔离加机非隔离
			1 => '无隔离',
			2 => '中央隔离',
			3 => '机非隔离',
			4 => '中央隔离加机非隔离',
		),
		'isolation_facility' => array( //中央隔离设施 01-绿化带 02-混凝土护栏 03-波形梁护栏 04-金属梁柱护栏 05-缆索护栏 06-活动护栏 07-隔离墩柱 99-其他
			'01' => '绿化带',
			'02' => '混凝土护栏',
			'03' => '波形梁护栏',
			'04' => '金属梁柱护栏',
			'05' => '缆索护栏',
			'06' => '缆索护栏',
			'07' => '隔离墩柱',
			'99' => '其他',
		),
		'roadside_protection' => array( //路侧防护设施 01-无防护 02-行道树 03-绿化带 04-混凝土护栏 05-波形梁护栏 06-金属梁柱护栏 07-缆索护栏 08-防护墩柱 09-缓冲物 10-避险车道 99-其他
			'01' => '无防护',
			'02' => '行道树',
			'03' => '绿化带',
			'04' => '混凝土护栏',
			'05' => '波形梁护栏',
			'06' => '金属梁柱护栏',
			'07' => '缆索护栏',
			'08' => '防护墩柱',
			'09' => '缓冲物',
			'10' => '避险车道',
			'99' => '其他',
		),
		'pavement_material' => array( //路面材料 1-沥青 2-水泥 3-沙石 4-土路 9-其他
			1 => '沥青',
			2 => '水泥',
			3 => '沙石',
			4 => '土路',
			9 => '其他',
		),
		'roadside_environment' => array( //路侧环境 01-房屋 02-围墙 03-广告牌 04-树木 05-灌木丛 06-平地 07-河流湖泊 08-边沟 09-护坡 10-山体 11-悬崖 12-高落差 19-其他
			'01' => '房屋',
			'02' => '围墙',
			'03' => '绿化带',
			'04' => '广告牌',
			'05' => '灌木丛',
			'06' => '平地',
			'07' => '河流湖泊',
			'08' => '边沟',
			'09' => '护坡',
			'10' => '山体',
			'11' => '悬崖',
			'12' => '高落差',
			'19' => '其他',
		),
		'speed_limit' => array( //道路限速标志设置情况 1-距事故点50m内 2-100m内 3-200m内 4-500m内 5-500m外 6-无
			1 => '距事故点50m内',
			2 => '100m内',
			3 => '200m内',
			4 => '500m内',
			5 => '500m外',
			6 => '无',
		),
		'bridge_type' => array( //桥梁类型 1-特大桥 2-大桥 3-中桥 4-小桥 5-涵洞
			1 => '特大桥',
			2 => '大桥',
			3 => '中桥',
			4 => '小桥',
			5 => '涵洞',
		),
		'baccident_point' => array( //桥梁 事故发生点所处位置 1-桥头处  2-桥梁中间  3-桥尾处
			1 => '桥头处',
			2 => '桥梁中间',
			3 => '桥尾处',
		),
		'lighting_conditions' => array( //照明条件 1-有照明 2-照明强度不足 3-无
			1 => '有照明',
			2 => '照明强度不足',
			3 => '无',
		),
		'induced_marker' => array( //诱导标志 1-有 2-无 3-标志不清楚
			1 => '有',
			2 => '无',
			3 => '标志不清楚',
		),
		'taccident_point' => array( //隧道 事故发生点所处位置 1-入口处 2-隧道中间 3-出口处
			1 => '入口处',
			2 => '隧道中间',
			3 => '出口处',
		),
		'rsafety_attribute' => array( //道路安全属性 1-正常路段 2-已经治理但仍存在隐患路段 3-正在治理隐患路段 4-尚未治理隐患路段 5-尚未排查隐患路段
			1 => '正常路段',
			2 => '已经治理但仍存在隐患路段',
			3 => '正在治理隐患路段',
			4 => '尚未治理隐患路段',
			5 => '尚未排查隐患路段',
		),
		'hidden_danger_pid' => array( //道路安全隐患类型 大分类
			1 => array('name' => '平曲线缺陷', 'value' => array('11' => '转弯半径过小', '12' => '连续弯道', '13' => '弯道有效视距不足', '19' => '其他平曲线缺陷')),
			2 => array('name' => '纵断面曲线缺陷', 'value' => array('21' => '连续长下坡(坡长___m)', '22' => '纵坡度过大(坡度___°)', '23' => '坡道终点接弯道', '24' => '坡道终点有效视距不足', '25' => '弯道设置反超高', '26' => '未设置必要的避险车道', '29' => '其他纵断面曲线缺陷')),
			3 => array('name' => '交叉口缺陷', 'value' => array('31' => '未进行有效渠化', '32' => '畸形路口导致冲突点过多', '33' => '未设置必要信号灯', '34' => '支路口未设置减速带', '35' => '支路口未设置让行标志', '39' => '其他交叉口缺陷')),
			4 => array('name' => '路面缺陷', 'value' => array('41' => '路面损毁', '42' => '路面坑洼', '43' => '路面冰雪未及时清理', '44' => '道路窨井盖缺失损坏', '45' => '路面有障碍物', '46' => '路面有散落物', '49' => '其他路面缺陷')),
			5 => array('name' => '道路防护设施缺陷', 'value' => array('51' => '未按标准设置中央隔离设施', '52' => '隔离设施强度不足', '53' => '无必要的路侧防护设施', '54' => '路侧防护设施强度不足', '55' => '安全防护设施损坏、灭失', '56' => '未设置必要机非隔离设施', '57' => '未设置必要防眩光设施', '59' => '其他道路防护设施缺陷')),
			6 => array('name' => '标志标线缺陷', 'value' => array('61' => '未施划交通标线', '62' => '缺乏必要的交通标志', '63' => '标志设置不合理', '64' => '标线设置不合理', '65' => '交通诱导信息不足', '66' => '指路标志信息不足', '67' => '其他交通信号问题', '69' => '其他标志标线缺陷')),
			7 => array('name' => '交通组织缺', 'value' => array('71' => '机动车通行秩序混乱', '72' => '非机动车占用机动车道', '73' => '非机动车穿行道路', '74' => '行人穿行道路', '75' => '占道摆摊设点', '76' => '占道晒粮打场', '77' => '前起事故现场交通秩序不良', '78' => '前起事故现场未及时清理', '79' => '其他交通组织缺陷')),
			8 => array('name' => '施工路段安全防护缺陷', 'value' => array('81' => '未按规定设置警示标志标牌', '82' => '未按规定设置减速标志', '83' => '未有效引导车流通过施工路段', '84' => '工路段交通组织混乱', '85' => '借道通行路段未有效设置隔离锥桶', '89' => '其他施工路段防护缺陷')),
			9 => array('name' => '道路环境缺陷', 'value' => array('91' => '建筑物、广告牌遮挡视线', '92' => '路侧树木遮挡视线', '93' => '道路横风过大', '94' => '道路缺乏必要照明', '95' => '照明亮度不足', '96' => '环境光源影响视线', '99' => '其他道路环境缺陷')),
		),

		'supervision_level' => array( //道路安全隐患督办等级 1-部级 2-省级 3-市级 4-县级 5-无
			1 => '部级',
			2 => '省级',
			3 => '市级',
			4 => '县级',
			5 => '无',
		),

	),
	'case_ext_reason' => array( //案件录入--交通事故信息采集补录--综合分析、接处警信息
		'alarm_man_type' => array( //报警人 1-事故车辆驾驶人 2-事故车辆乘员 3-目击群众 4-过路群众 5-单位企业 6-路政部门 7-医疗机构 8-当地派出所 9-其他
			1 => '事故车辆驾驶人',
			2 => '事故车辆乘员',
			3 => '目击群众',
			4 => '过路群众',
			5 => '单位企业',
			6 => '路政部门',
			7 => '医疗机构',
			8 => '当地派出所',
			9 => '其他',
		),
		'reason_type' => array(

			1 => array(
				'name' => '人的原因',
				'value' => array(
					1 => array('name' => '注意力分散', 'value' => array('8001' => '受车内人员影响', '8002' => '与乘车人聊天', '8003' => '看车内同乘人员、物体等', '8004' => '捡拾车内物品', '8005' => '专注听收音机或音乐', '8006' => '操作电视机、收音机、车载电台等', '8007' => '看导航仪、地图', '8008' => '搜寻道路、指路标识', '8009' => '看风景、地形', '8010' => '看其他车辆、行人', '8011' => '看倒车镜或外后视镜', '8012' => '专注考虑其他事情', '8013' => '走神', '8049' => '其他注意力分散')),
					2 => array('name' => '状态不良', 'value' => array('8050' => '驾驶前未充分休息', '8052' => '途中未充分休息', '8053' => '感冒、发烧', '8054' => '高血压等慢性病', '8055' => '突发性疾病', '8056' => '睡眠疾病', '8057' => '服用镇静类或可导致瞌睡药物', '8058' => '服用依赖性精神药品成瘾', '8099' => '其他状态不良')),
					3 => array('name' => '判断错误', 'value' => array('8101' => '预测对方行动错误', '8102' => '以为对方会让路或停车让路', '8103' => '运行(速度、距离等)预测错误', '8104' => '避让危险(事故)判断错误', '8105' => '路线选择错误', '8106' => '对公路形状、线形认识错误', '8107' => '对路面状况判断错误', '8108' => '对路面宽度判断错误', '8109' => '对路面障碍物认知错误', '8110' => '对道路标志标线认知错误', '8111' => '对交通安全设施认知错误', '8149' => '其他判断错误')),
					4 => array('name' => '操作错误', 'value' => array('8151' => '踩刹车误踩油门', '8152' => '踩刹车力度不够', '8153' => '踩刹车力度过大', '8154' => '转向操作不正确', '8155' => '油门控制不当', '8156' => '挡位操作不当', '8157' => '避让危险（事故）措施不当', '8199' => '其他操作错误')),
				),
			),
			2 => array(
				'name' => '车辆原因',
				'value' => array(
					1 => array(
						'name' => '车辆及装置',
						'value' => array(
							1 => array(
								'name' => '制动',
								'value' => array('8301' => '制动不良', '8302' => '制动热衰退', '8303' => '制动失效', '8304' => '制动跑偏', '8319' => '其他制动问题'),
							),
							2 => array(
								'name' => '转向',
								'value' => array('8321' => '转向不良', '8322' => '转向失效', '8339' => '其他转向问'),
							),
							3 => array(
								'name' => '轮胎',
								'value' => array('8341' => '爆胎', '8342' => '轮胎脱落', '8343' => '外胎开裂', '8344' => '胎冠花纹深度不足', '8345' => '同轴轮胎规格/花纹不一致', '8346' => '车轮安装不正确', '8347' => '轮胎不符合规格', '8359' => '其他轮胎问题'),
							),
							4 => array(
								'name' => '灯光信号',
								'value' => array('8361' => '前照灯不合格', '8362' => '雾灯不合格', '8363' => '后位灯不合格', '8364' => '转向灯不合格', '8365' => '制动信号灯不合格', '8366' => '危险警告信号不合格', '8367' => '货车车身反光标志效果不明显', '8379' => '其他灯光问题'),
							),
							5 => array(
								'name' => '车身',
								'value' => array('8381' => '车辆前部强度不足', '8382' => '侧部强度不足', '8383' => '后部强度不足', '8384' => '顶部强度不足', '8385' => '抗翻滚性能不良', '8386' => '货车侧面防护装置强度不足', '8387' => '货车后下部防撞装置强度不足', '8399' => '其他车身问题'),
							),
							6 => array(
								'name' => '其他部件',
								'value' => array('8401' => '发动机故障', '8402' => '变速器不良', '8403' => '燃料、润滑装置不良', '8404' => '前风挡玻璃破裂、脱落', '8405' => '雨刮器故障、破损', '8419' => '其他'),
							),
						),
					),
					2 => array(
						'name' => '车辆运行状态',
						'value' => array(
							1 => array(
								'name' => '视线状态',
								'value' => array('8451' => '车辆前向有效视区不足', '8452' => '车辆侧向有效视区不足', '8453' => '车辆后向有效视区不足', '8454' => '后视镜视区不足', '8455' => '前风窗玻璃不洁影响视线', '8456' => '遮光膜影响视线', '8457' => '车内装饰物影响视野', '8458' => '车内的同乘人员影响视线及操作', '8459' => '所载货物影响视线及操作', '8479' => '其他视线问题'),
							),
							2 => array(
								'name' => '载货状态',
								'value' => array('8481' => '货物散落', '8482' => '货物固定不牢', '8483' => '车辆重心改变', '8484' => '载货等妨碍车辆的灯光', '8499' => '其他载货问题'),
							),

						),
					),
				),
			),
			3 => array(
				'name' => '道路环境原因',
				'value' => array(
					1 => array(
						'name' => '道路设施',
						'value' => array(
							1 => array(
								'name' => '路面',
								'value' => array('8601' => '道路平曲线缺陷', '8602' => '道路纵断面曲线缺陷', '8603' => '道路交叉口形状不良', '8604' => '有效视距不足', '8605' => '路面损毁', '8606' => '路面有散落物', '8607' => '其他障碍物', '8608' => '道路施工', '8609' => '路面积水', '8610' => '路面冰雪', '8649' => '其他路面问题'),
							),
							2 => array(
								'name' => '安全设施',
								'value' => array('8651' => '未按标准设置中央隔离设施', '8652' => '隔离设施强度不足', '8653' => '无必要的路侧防护设施', '8654' => '路侧防护设施强度不足', '8655' => '安全防护设施损坏、灭失', '8656' => '道路施工无安全防护措施', '8657' => '缺乏安全过街设施', '8699' => '其他设施问题'),
							),

						),
					),
					2 => array(
						'name' => '交通组织',
						'value' => array(
							1 => array(
								'name' => '交通信号',
								'value' => array('8701' => '未施划交通标线', '8702' => '缺乏必要的交通标志', '8703' => '标志设置不合理', '8704' => '标线设置不合理', '8705' => '交通诱导信息不足', '8706' => '指路标志信息不足', '8707' => '道路施工未设置标志', '8749' => '其他交通信号问题'),
							),
							2 => array(
								'name' => '干扰因素',
								'value' => array('8751' => '机动车干扰', '8752' => '非机动车干扰', '8753' => '行人干扰', '8754' => '交通秩序混乱', '8755' => '施工路段交通组织混乱', '8756' => '前起事故现场交通秩序不良', '8757' => '前起事故现场未及时清理', '8758' => '占道经营干扰', '8759' => '噪音干扰', '8799' => '其他干扰'),
							),

						),
					),
					3 => array(
						'name' => '环境',
						'value' => array(
							1 => array(
								'name' => '视线状态',
								'value' => array('8801' => '静止车辆影响视线', '8802' => '运动车辆影响视线', '8803' => '建筑物、树木等影响视线', '8804' => '天气（雨、雾、雪）影响视线', '8805' => '对向车辆灯光影响视线', '8806' => '环境光源影响视线', '8807' => '照明条件不良', '8849' => '其他视线问题'),
							),
							2 => array(
								'name' => '自然灾害',
								'value' => array('8851' => '洪水', '8852' => '泥石流', '8853' => '滑坡', '8854' => '塌方', '8855' => '台风、飓风', '8856' => '地震', '8899' => '其他自然灾害'),
							),

						),
					),

				),
			),
		),
	),

	'case_ext_client' => array( //案件录入--交通事故信息采集补录--当事人信息
		'client_attr' => array( //当事人属性  1-个人 2-单位
			1 => '个人',
			2 => '单位',
		),
		'census_region' => array( //户籍所在地行政区划

		),
		'census_attr' => array( //户口性质  1-非农业户口 2-农业户口
			1 => '非农业户口',
			2 => '农业户口',
		),
		'person_type' => array( //人员类型  11-公务员 12-公安民警 13-职员 14-工人 15-农民 16-自主经营者 21-军人 22-武警 31-教师 32-大(专)学生 33-中(专)学生 34-小学生 35-学前儿童 41-港澳台胞 42-华侨  43-外国人 51-外来务工者 52-不在业人员 99-其他
			'11' => '公务员',
			'12' => '公安民警',
			'13' => '职员',
			'14' => '工人',
			'15' => '农民',
			'16' => '自主经营者',
			'21' => '军人',
			'22' => '武警',
			'31' => '教师',
			'32' => '大(专)学生',
			'33' => '中(专)学生',
			'34' => '小学生',
			'35' => '学前儿童',
			'41' => '港澳台胞',
			'42' => '华侨',
			'43' => '外国人',
			'51' => '外来务工者',
			'52' => '不在业人员',
			'99' => '其他',
		),
		'traffic_type' => array(
			1 => array('name' => '驾驶机动车', 'value' => array('K1' => '大型客车', 'K2' => '中型客车', 'K3' => '小型客车', 'K4' => '微型客车', 'H1' => '重型货车', 'H2' => '中型货车', 'H3' => '轻型货车', 'H4' => '微型货车', 'N1' => '三轮汽车', 'N2' => '低速汽车', 'Q1' => '其他汽车', 'G' => '汽车列车', 'M1' => '普通摩托车', 'M2' => '轻便摩托车', 'T1' => '拖拉机', 'J1' => '其他机动车')),
			2 => array('name' => '驾驶非机动车', 'value' => array('F1' => '自行车', 'F2' => '三轮车', 'F3' => '手推车', 'F4' => '残疾人机动轮椅车', 'F5' => '畜力车', 'F6' => '电动自行车', 'F9' => '其他非机动车')),
			3 => array('name' => '乘车', 'value' => array('C1' => '乘大中型客车', 'C2' => '乘小微型客车', 'C3' => '乘普通货车', 'C4' => '乘汽车列车', 'C5' => '乘三轮汽车和低速货车', 'C6' => '乘摩托车', 'C7' => '乘拖拉机', 'C8' => '乘其他机动车', 'C9' => '乘其他非机动车')),
			4 => array('name' => '步行', 'value' => array('A1' => '步行')),
			5 => array('name' => '其他', 'value' => array('X9' => '其他')),
		),
		'driver_license_type' => array( //驾驶证种类  1-机动车 2-拖拉机 3-军队 4-武警 5-无驾驶证
			1 => '机动车',
			2 => '拖拉机',
			3 => '军队',
			4 => '武警',
			5 => '无驾驶证',
		),
		'driver_attr' => array( // 驾驶人属性  1-职业驾驶人 2-非职业驾驶人 3-不明
			1 => '职业驾驶人',
			2 => '非职业驾驶人',
			3 => '不明',
		),
		'drive_license_illegal' => array( // 驾驶证合法状态  1-职业驾驶人 2-非职业驾驶人 3-不明
			1 => '职业驾驶人',
			2 => '非职业驾驶人',
			3 => '不明',
		),
		'series_times' => array( // 连续驾驶时间  1-2h以内 2-2～4h 3-4～8h 4-8h以上 9-不明
			1 => '2h以内',
			2 => '2～4h',
			3 => '4～8h',
			4 => '8h以上',
			9 => '不明',
		),
		'vacc_familiar' => array( // 对事故车辆熟悉程度  1-初次驾驶 2-近一年内驾驶2至10次 3--近一年内驾驶超过10次
			1 => '初次驾驶',
			2 => '近一年内驾驶2至10次',
			3 => '近一年内驾驶超过10次',
		),
		'rtrav_familiar' => array( //  对行驶线路熟悉程度  1-初次行驶 2-近一年内行驶2至10次 3--近一年内行驶超过10次
			1 => '初次驾驶',
			2 => '近一年内驾驶2至10次',
			3 => '近一年内驾驶超过10次',
		),
		'odriver_qualificate' => array( //  营运驾驶人从业资格  1-正常 2-无资格证 3-资格证失效 4-不明
			1 => '正常',
			2 => '无资格证',
			3 => '资格证失效',
			4 => '不明',
		),
		'dgtrans_qualificate' => array( //  危险品运输从业资格  1-正常 2-无资格证 3-资格证失效 4-不明
			1 => '正常',
			2 => '无资格证',
			3 => '资格证失效',
			4 => '不明',
		),
		'ride_condition' => array( //  乘坐情况  1-驾驶位置 2-前排其他位置或摩托车乘员  3-乘员区左侧 4-乘员区右侧 5-乘员区中间 6-站立 7-货箱 8-不明
			1 => '驾驶位置',
			2 => '前排其他位置或摩托车乘员',
			3 => '乘员区左侧',
			4 => '乘员区右侧',
			5 => '乘员区中间',
			6 => '站立',
			7 => '货箱',
			8 => '不明',
		),
		'acollision_position' => array( //  碰撞后位置  1-被抛出车外 2-脱离座位 3-原座位 4-不明
			1 => '被抛出车外',
			2 => '脱离座位',
			3 => '原座位',
			4 => '不明',
		),
		'invasion_situation' => array( //  乘员保护区被侵入情况  1-被侵入 2-未被侵入 3-不明
			1 => '被侵入',
			2 => '未被侵入',
			3 => '不明',
		),

		'alcohol_content' => array( //血液酒精含量  1-0～20（mg/100mL） 2-20～80（mg/100mL） 3-大于等于80（mg/100mL）  4-未查
			1 => '0～20（mg/100mL）',
			2 => '20～80（mg/100mL）',
			3 => '大于等于80（mg/100mL）',
			4 => '未查',
		),
		'safety_devices' => array( //安全保护装置使用情况  1-使用安全带/头盔 2-未使用安全带/头盔 3-使用儿童安全座椅 4-未使用儿童安全座椅 9-不明
			1 => '使用安全带/头盔',
			2 => '未使用安全带/头盔',
			3 => '使用儿童安全座椅',
			4 => '未使用儿童安全座椅',
			9 => '不明',
		),
		'pedestrian_condition' => array( //行人状态  01-正常通行 02-过人行横道 03-横穿道路 04-翻越隔离设施 05-在机动车道内行走 06-在路上游戏 07-在路上作业 08-在路上停留 99-其他
			'01' => '正常通行',
			'02' => '过人行横道',
			'03' => '横穿道路',
			'04' => '翻越隔离设施',
			'05' => '在机动车道内行走',
			'06' => '在路上游戏',
			'07' => '在路上作业',
			'08' => '在路上停留',
			'99' => '其他',
		),
		'pedestrian_speed' => array( //行人速度  1-静止 2-慢行 3-正常 4-快行 5-跑 9-其他
			1 => '静止',
			2 => '慢行',
			3 => '正常',
			4 => '快行',
			5 => '跑',
			9 => '其他',
		),
		'accident_rb' => array( //事故责任  1-全部 2-主要 3-同等 4-次要 5-无责 6-无法认定
			1 => '全部',
			2 => '主要',
			3 => '同等',
			4 => '次要',
			5 => '无责',
			6 => '无法认定',
		),
		'injury_degree' => array( //伤害程  1-死亡 2-重伤 3-轻伤 4-不明 5-无伤害
			1 => '死亡',
			2 => '重伤',
			3 => '轻伤',
			4 => '不明',
			5 => '无伤害',
		),
		'law_pid' => array( //违法行为
			1 => '主要违法行为',
			2 => '其他违法行为一',
			3 => '其他违法行为二',
		),

		'damage_property' => array( //伤害性质  1-骨折 2-扭伤、拉伤 3-脑震荡、脑挫裂伤 4-锐器伤、开放伤 5-挫伤、擦伤 6-烧烫伤 7-器官系统损伤 8-软组织损伤 9-其他
			1 => '骨折',
			2 => '扭伤、拉伤',
			3 => '脑震荡、脑挫裂伤',
			4 => '锐器伤、开放伤',
			5 => '挫伤、擦伤',
			6 => '烧烫伤',
			7 => '器官系统损伤',
			8 => '软组织损伤',
			9 => '其他',
		),

		'injury_area' => array( //受伤部位  1-头部 2-颈部 3-上肢 4-下肢 5-胸、背部 6-腰、腹部 7-多部位 9-其他
			1 => '头部',
			2 => '颈部',
			3 => '上肢',
			4 => '下肢',
			5 => '胸、背部',
			6 => '腰、腹部',
			7 => '多部位',
			9 => '其他',
		),
		'death_reason' => array( //致死原因  1-颅脑损伤 2-胸腹损伤 3-创伤失血性休克 4-窒息 5-直接烧死 9-其他
			1 => '颅脑损伤',
			2 => '胸腹损伤',
			3 => '创伤失血性休克',
			4 => '窒息',
			5 => '直接烧死',
			9 => '其他',
		),
		'penalty' => array( //行政处罚  1-无 2-罚款 3-记分 4-暂扣驾驶证 5-吊销驾驶证 6-行政拘留
			1 => '无',
			2 => '罚款',
			3 => '记分',
			4 => '暂扣驾驶证',
			5 => '吊销驾驶证',
			6 => '行政拘留',
		),
	),

);

?>