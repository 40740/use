<?php

add_action('widgets_init', 'widget_register_more');
function widget_register_more()
{
	register_widget('widget_ui_yiyan');
	register_widget('widget_ui_posts_navs');
	register_widget('widget_ui_new_comment');
	register_widget('widget_ui_links_lists_2');
	register_widget('widget_ui_notice');
	register_widget('widget_ui_search');
	register_widget('widget_ui_tag_cloud');
}

/**
 * 标签云
 */

class widget_ui_tag_cloud extends WP_Widget
{
	public function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_tag_cloud',
			'w_name'     =>  _name('标签云'),
			'classname'     => '',
			'description'       => '显示标签、分类、专题的标签云',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	public function widget($args, $instance)
	{
		extract($args);
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'more_but' => '',
			'more_but_url' => '',
			'in_affix' => '',

			'taxonomy' => 'post_tag',
			'show_count' => '',
			'orderby' => 'name',
			'number' => 20,
			'blank' => '',
			'color' => 'rand',
			'fixed_width' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults);

		$in_affix = $instance['in_affix'] ? ' data-affix="true"' : '';
		echo '<div' . $in_affix . ' class="theme-box">';
		$title = apply_filters('zib_widget_title', $instance);
		echo $title;
		echo '<div class="zib-widget widget-tag-cloud author-tag' . ($instance['fixed_width'] ? ' fixed-width' : '') . '">';

		//新窗口打开
		$blank = $instance['blank'] ? ' target="_blank"' : '';

		//开始生成标签
		$get_terms_args = array(
			'taxonomy'   => $instance['taxonomy'],
			'orderby'   => $instance['orderby'],
			'order'   => 'DESC',
			'number'   => $instance['number'],
			'hide_empty'   => false,
			'count'   => true,
		);
		$tags = get_terms($get_terms_args);

		$tag_link = '';
		$rand_color_i = rand(0, 10);
		if (!empty($tags) && !is_wp_error($tags)) {
			foreach ($tags as $key => $tag) {
				$url = esc_url(get_term_link(intval($tag->term_id), $tag->taxonomy));
				$name = esc_attr($tag->name);
				$cls = array('c-blue', 'c-yellow', 'c-green', 'c-purple', 'c-red', '', 'c-blue-2', 'c-yellow-2', 'c-green-2',  'c-purple-2', 'c-red-2', '');
				if ($rand_color_i > 10) $rand_color_i = 0;
				$tag_class = 'but ' . ($instance['color'] != 'rand' ? $instance['color'] : $cls[$rand_color_i]);
				$rand_color_i++;
				$count = $instance['show_count'] ? '<span class="em09 tag-count"> (' . esc_attr($tag->count) . ')</span>' : '';
				$tag_link .= '<a' . $blank . ' href="' . $url . '" class="text-ellipsis ' . $tag_class . '">' . $name . $count . '</a>';
			}
		}
		echo $tag_link;
		echo '</div>';
		echo '</div>';
	}
	public function form($instance)
	{
		$defaults = array(
			'title' => '标签云',
			'mini_title' => '',
			'more_but' => '<i class="fa fa-angle-right fa-fw"></i>更多',
			'more_but_url' => '',
			'in_affix' => '',

			'taxonomy' => 'post_tag',
			'show_count' => '',
			'orderby' => 'name',
			'number' => 20,
			'blank' => '',
			'color' => 'rand',
			'fixed_width' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults);

		$page_input[] = array(
			'name' => __('标题：', 'zib_language'),
			'id' => $this->get_field_name('title'),
			'std' => $instance['title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('副标题：', 'zib_language'),
			'id' => $this->get_field_name('mini_title'),
			'std' => $instance['mini_title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->文案：', 'zib_language'),
			'id' => $this->get_field_name('more_but'),
			'std' => $instance['more_but'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->链接：', 'zib_language'),
			'id' => $this->get_field_name('more_but_url'),
			'std' => $instance['more_but_url'],
			'desc' => '设置为任意链接',
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'id' => $this->get_field_name('in_affix'),
			'std' => $instance['in_affix'],
			'desc' => '侧栏随动 (仅在侧边栏有效)',
			'style' => 'margin: 10px auto;',
			'type' => 'checkbox',
		);
		$taxonomies = get_taxonomies(array('show_tagcloud' => true), 'object');
		foreach ($taxonomies as $taxonomy => $tax) {
			$options[$taxonomy] = $tax->labels->name;
		}
		$page_input[] = array(
			'name' => __('分类法', 'zib_language'),
			'id' => $this->get_field_name('taxonomy'),
			'std' => $instance['taxonomy'],
			'style' => 'margin: 10px auto;',
			'type' => 'select',
			'options' => $options
		);
		$page_input[] = array(
			'name' => __('排序方式', 'zib_language'),
			'id' => $this->get_field_name('orderby'),
			'std' => $instance['orderby'],
			'style' => 'margin: 10px auto;',
			'type' => 'select',
			'options' => array(
				'name' => '名称',
				'count' => '数量',

			)
		);
		$page_input[] = array(
			'name' => __('标签颜色', 'zib_language'),
			'id' => $this->get_field_name('color'),
			'std' => $instance['color'],
			'style' => 'margin: 10px auto;',
			'type' => 'select',
			'options' => array(
				'rand' => '随机颜色',
				'c-hui' => '灰色',
				'c-blue' => '蓝色',
				'c-blue-2' => '深蓝色',
				'c-yellow' => '黄色',
				'c-yellow-2' => '橙黄色',
				'c-green' => '绿色',
				'c-green-2' => '墨绿色',
				'c-purple' => '紫色',
				'c-purple-2' => '深紫色',
				'c-red' => '粉红色',
				'c-red-2' => '红色',
			)
		);

		$page_input[] = array(
			'name' => __('最大数量', 'zib_language'),
			'id' => $this->get_field_name('number'),
			'std' => $instance['number'],
			'style' => 'margin: 10px auto;',
			'type' => 'number',
		);
		$page_input[] = array(
			'id' => $this->get_field_name('show_count'),
			'std' => $instance['show_count'],
			'desc' => '显示标签计数',
			'style' => 'margin: 10px auto;',
			'type' => 'checkbox',
		);
		$page_input[] = array(
			'id' => $this->get_field_name('fixed_width'),
			'std' => $instance['fixed_width'],
			'desc' => '固定宽度',
			'style' => 'margin: 10px auto;',
			'type' => 'checkbox',
		);
		$page_input[] = array(
			'id' => $this->get_field_name('blank'),
			'std' => $instance['blank'],
			'desc' => '新窗口打开',
			'style' => 'margin: 10px auto;',
			'type' => 'checkbox',
		);
		echo zib_edit_input_construct($page_input);
	}
}

/**
 *搜索小工具
 */
class widget_ui_search extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_search',
			'w_name'     =>  _name('搜索框'),
			'classname'     => '',
			'description'       => '显示一个搜索框，多种显示效果',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}

	function widget($args, $instance)
	{
		extract($args);
		$defaults = array(
			'title' => '搜索',
			'mini_title' => '',

			'more_but' => '',
			'more_but_url' => '',
			'in_affix' => '',

			'show_history' => '',

			'class' => '',
			'show_keywords' => '',
			'keywords_title' => '热门搜索',
			'placeholder' => '开启精彩搜索',
			'show_input_cat' =>  '',
			'show_more_cat' => '',
			'in_cat' => '',
			'more_cats' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults);

		$in_affix = $instance['in_affix'] ? ' data-affix="true"' : '';
		echo '<div' . $in_affix . ' class="theme-box">';

		$title = apply_filters('zib_widget_title', $instance);
		echo $title;
		echo '<div class="zib-widget widget-search">';

		$args = array(
			'class' => '',
			'show_keywords' => $instance['show_keywords'],
			'show_history' => $instance['show_history'],
			'keywords_title' => $instance['keywords_title'],
			'placeholder' => $instance['placeholder'],
			'show_input_cat' =>  $instance['show_input_cat'],
			'show_more_cat' => $instance['show_more_cat'],
			'in_cat' => $instance['in_cat'],
		);
		if ($instance['more_cats']) {
			$args['more_cats'] = preg_split("/,|，|\s|\n/", $instance['more_cats']);
		}
		zib_get_search($args);

		echo '</div>';
		echo '</div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '搜索',
			'mini_title' => '',
			'more_but' => '<i class="fa fa-angle-right fa-fw"></i>更多',
			'more_but_url' => '',

			'class' => '',
			'show_history' => '',
			'show_keywords' => '',
			'keywords_title' => '热门搜索',
			'placeholder' => '开启精彩搜索',
			'show_input_cat' =>  '',
			'show_more_cat' => '',
			'in_cat' => '',
			'in_affix' => '',
			'more_cats' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		$page_input = array();

		$page_input[] = array(
			'name' => __('标题：', 'zib_language'),
			'id' => $this->get_field_name('title'),
			'std' => $instance['title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('副标题：', 'zib_language'),
			'id' => $this->get_field_name('mini_title'),
			'std' => $instance['mini_title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->文案：', 'zib_language'),
			'id' => $this->get_field_name('more_but'),
			'std' => $instance['more_but'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->链接：', 'zib_language'),
			'id' => $this->get_field_name('more_but_url'),
			'std' => $instance['more_but_url'],
			'desc' => '设置为任意链接',
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);

		echo zib_edit_input_construct($page_input);
?>

		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>

		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_keywords'], 'on'); ?> id="<?php echo $this->get_field_id('show_keywords'); ?>" name="<?php echo $this->get_field_name('show_keywords'); ?>"> 显示热门搜索关键词
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_history'], 'on'); ?> id="<?php echo $this->get_field_id('show_history'); ?>" name="<?php echo $this->get_field_name('show_history'); ?>"> 显示用户搜索历史
			</label>
		</p>
		<p>
			<label>
				热门搜索-标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('keywords_title');
												?>" name="<?php echo $this->get_field_name('keywords_title');
															?>" type="text" value="<?php echo $instance['keywords_title'];
																					?>" />
			</label>
		</p>

		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_input_cat'], 'on'); ?> id="<?php echo $this->get_field_id('show_input_cat'); ?>" name="<?php echo $this->get_field_name('show_input_cat'); ?>"> 显示分类
			</label>
		</p>

		<p>
			<label>
				默认已选择的分类：
				<select style="width:100%;" name="<?php echo $this->get_field_name('in_cat'); ?>">
					<?php echo zib_widget_option('cat', $instance['in_cat']); ?>
				</select>
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_more_cat'], 'on'); ?> id="<?php echo $this->get_field_id('show_more_cat'); ?>" name="<?php echo $this->get_field_name('show_more_cat'); ?>"> 显示更多分类选择框
			</label>
		</p>
		<p>
			<label>
				更多分类的ID（默认为全部分类，如需自定义则将分类的ID填入下方，多个ID用逗号隔开）：
				<input style="width:100%;" id="<?php echo $this->get_field_id('more_cats');
												?>" name="<?php echo $this->get_field_name('more_cats');
															?>" type="text" value="<?php echo $instance['more_cats'];
																					?>" />
			</label>
		</p>
	<?php
	}
}


////---------公告栏--------、、、、、、、
class widget_ui_notice extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_notice',
			'w_name'     =>  _name('滚动公告'),
			'in_affix' => '',
			'classname'     => '',
			'description'       => '可做公告栏或者其他滚动显示内容',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	function form($instance)
	{
		$defaults = array(
			'blank'  => '',
			'alignment'   => '',
			'radius'   => '',
			'null'   => '',
			'in_affix' => '',
			'color'   => 'c-blue',
			'img_ids' => array(),
		);

		$defaults['img_ids'][] = array(
			'title' => '子比主题，更优雅的Wordpress主题',
			'icon' => 'fa-home',
			'href'   => 'https://zibll.com',
		);

		$defaults['img_ids'][] = array(
			'title' => '更优雅的WordPress网站主题：子比主题！全面开启',
			'icon' => 'fa-home',
			'href'   => 'https://zibll.com',
		);

		$instance = wp_parse_args((array) $instance, $defaults);

		$img_html = '';
		$img_i = 0;

		foreach ($instance['img_ids'] as $category) {
			$_tt = '<div class="panel"><h4 class="panel-title">消息' . ($img_i + 1) . '：' . $instance['img_ids'][$img_i]['title'] . '</h4><div class="panel-hide panel-conter">';
			$_html_a = '<label>消息' . ($img_i + 1) . '-内容（必填）：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].title" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][title]" value="' . $instance['img_ids'][$img_i]['title'] . '" /></label>';
			$_html_b = '<label>消息' . ($img_i + 1) . '-图标（填写FA图标class）：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].icon" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][icon]" value="' . $instance['img_ids'][$img_i]['icon'] . '" /></label>';
			$_html_b .= '<label>消息' . ($img_i + 1) . '-链接：<input style="width:100%;" type="text" id="' . $this->get_field_id('img_ids') . '[' . $img_i . '].href" name="' . $this->get_field_name('img_ids') . '[' . $img_i . '][href]" value="' . $instance['img_ids'][$img_i]['href'] . '" /></label>';

			$_tt2 = '</div></div>';
			$img_html .= '<div class="widget_ui_slider_g">' . $_tt . $_html_a . $_html_b . $_tt2 . '</div>';
			$img_i++;
		}

		$add_b = '<button type="button" data-name="' . $this->get_field_name('img_ids') . '" data-count="' . $img_i . '" class="button add_button add_notice_button">添加栏目</button>';
		$add_b .= '<button type="button" data-name="' . $this->get_field_name('img_ids') . '" data-count="' . $img_i . '" class="button rem_lists_button">删除栏目</button>';
		$img_html .= $add_b;
		//echo '<pre>' . json_encode($instance) . '</pre>';
	?>
		<p>
			显示一个公告栏，多个消息滚动显示,请注意控制长度，否则在移动端显示不全
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['blank'], 'on'); ?> id="<?php echo $this->get_field_id('blank'); ?>" name="<?php echo $this->get_field_name('blank'); ?>"> 链接新窗口打开
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['radius'], 'on'); ?> id="<?php echo $this->get_field_id('radius'); ?>" name="<?php echo $this->get_field_name('radius'); ?>"> 两边显示为圆形
			</label>
		</p>
		<p>
			<label>
				主题色彩：
				<select style="width:100%;" name="<?php echo $this->get_field_name('color'); ?>">
					<option value="c-red" <?php selected('c-red', $instance['color']); ?>>透明粉红</option>
					<option value="c-yellow" <?php selected('c-yellow', $instance['color']); ?>>透明黄</option>
					<option value="c-blue" <?php selected('c-blue', $instance['color']); ?>>透明蓝</option>
					<option value="c-green" <?php selected('c-green', $instance['color']); ?>>透明绿</option>
					<option value="c-purple" <?php selected('c-purple', $instance['color']); ?>>透明紫</option>
					<option value="c-red-2" <?php selected('c-red', $instance['color']); ?>>透明红</option>
					<option value="c-yellow-2" <?php selected('c-yellow', $instance['color']); ?>>透明橘黄</option>
					<option value="c-blue-2" <?php selected('c-blue', $instance['color']); ?>>透明深蓝</option>
					<option value="c-green-2" <?php selected('c-green', $instance['color']); ?>>透明墨绿</option>
					<option value="c-purple-2" <?php selected('c-purple', $instance['color']); ?>>透明深紫</option>
					<option value="b-theme sbg" <?php selected('b-theme', $instance['color']); ?>>主题色</option>
					<option value="b-red sbg" <?php selected('b-red', $instance['color']); ?>>红色</option>
					<option value="b-yellow sbg" <?php selected('b-yellow', $instance['color']); ?>>黄色</option>
					<option value="b-blue sbg" <?php selected('b-blue', $instance['color']); ?>>蓝色</option>
					<option value="b-green sbg" <?php selected('b-green', $instance['color']); ?>>绿色</option>
					<option value="b-purple sbg" <?php selected('b-purple', $instance['color']); ?>>紫色</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				对齐方式：
				<select style="width:100%;" name="<?php echo $this->get_field_name('alignment'); ?>">
					<option value="" <?php selected('', $instance['alignment']); ?>>靠左</option>
					<option value="text-center" <?php selected('text-center', $instance['alignment']); ?>>居中</option>
					<option value="text-right" <?php selected('text-right', $instance['alignment']); ?>>靠右</option>
				</select>
			</label>
		</p>
		<div class="widget_ui_slider_lists">
			<?php echo $img_html; ?>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox hide" type="checkbox" <?php checked($instance['null'], 'on'); ?> id="<?php echo $this->get_field_id('null'); ?>" name="<?php echo $this->get_field_name('null'); ?>"><a class="button ok_button">应用</a>
			</label>
		</div>
		<?php wp_enqueue_media(); ?>
	<?php
	}

	function widget($args, $instance)
	{

		extract($args);

		$defaults = array(
			'blank'  => '',
			'alignment'   => '',
			'radius'   => '',
			'null'   => '',
			'in_affix' => '',
			'color'   => 'c-blue',
			'img_ids' => array(),
		);

		$defaults['img_ids'][] = array(
			'title' => '子比主题开始公测啦！正版授权，限时免费！',
			'icon' => 'fa-home',
			'href'   => 'https://zibll.com',
		);

		$defaults['img_ids'][] = array(
			'title' => '更优雅的WordPress网站主题：子比主题！全面开启',
			'icon' => 'fa-home',
			'href'   => 'https://zibll.com',
		);

		$instance = wp_parse_args((array) $instance, $defaults);

		$links = array(
			'class'   => $instance['alignment'] . ' ' . $instance['color'] . ($instance['radius'] ? ' radius' : ' radius8'),
		);
		foreach ($instance['img_ids'] as $slide_img) {
			if ($slide_img['title']) {
				$slide = array(
					'title'   => $slide_img['title'],
					'href'   => $slide_img['href'],
					'blank'  => $instance['blank'],
					'icon'     => $slide_img['icon']
				);
				$links['notice'][] = $slide;
			}
		}
		$in_affix = $instance['in_affix'] ? ' data-affix="true"' : '';
		echo '<div' . $in_affix . ' class="theme-box">';
		zib_notice($links);
		echo '</div>';

		//echo '<pre>'.json_encode($instance).'</pre>';
	?>

	<?php
	}
}


//---------公告栏---
/**
Zib_CFSwidget::create('zib_widget_ui_notice', array(
	'title'       => '滚动公告',
	'zib_title'   => false,
	'zib_affix'   => true,
	'zib_show'    => true,
	'description' => '可做公告栏或者其他滚动显示内容',
	'fields'      => array(
		array(
			'title' => __("对齐方式", 'zib_language'),
			'id' => 'alignment',
			'type' => 'radio',
			'default' => '',
			'options' => array(
				'' => '靠左',
				'text-center' => '居中',
				'text-right' => '靠右',
			),
		),
		array(
			'title' => '两边显示为圆形',
			'id' => 'radius',
			'type' => 'switcher',
			'default' => false,
		),
		array(
			'title' => '主题色彩',
			'id' => 'color',
			'class' => 'skin-color',
			'default' => "c-blue",
			'type' => "palette",
			'options' => CFS_Module::zib_palette()
		),
		array(
			'id'     => 'notice',
			'type'   => 'group',
			'min'   => 1,
			'button_title' => '添加公告',
			'title'  => '消息公告',
			'default'   => array(
				array(
					'link' => array(
						'url'    => 'https://www.zibll.com/',
						'target'    => '_blank',
					),
					'title'   => '子比主题-更优雅的Wordpress主题',
					'icon' => 'fa fa-home',
					'class' => 'c-blue',
				),
			),
			'fields' => array(
				array(
					'id'    => 'title',
					'type'  => 'text',
					'title' => '公告内容',
					'default' => "",
				),
				array(
					'dependency' => array('title', '!=', ''),
					'id'      => 'icon',
					'class' => 'compact',
					'type'    => 'icon',
					'title'   => '显示图标',
					'button_title'   => '选择图标',
					'default' => 'fa fa-home'
				),
				array(
					'dependency' => array('title', '!=', ''),
					'id'    => 'link',
					'class' => 'compact',
					'type'  => 'link',
					'title' => ' ',
					'add_title'    => '添加链接',
					'edit_title'   => '编辑链接',
					'remove_title' => '删除链接',
					'default' => array(),
				),
			),
		),
	)
));

function zib_widget_ui_notice($args, $instance)
{
	$show_class = Zib_CFSwidget::show_class($instance);
	if (!$show_class) return;

	$class = $instance['alignment'] . ' ' . $instance['color'] . ($instance['radius'] ? ' radius' : ' radius8');

	$links = array();
	$links = array(
		'class'   => $instance['alignment'] . ' ' . $instance['color'] . ($instance['radius'] ? ' radius' : ' radius8'),
	);
	$links['notice'] = $instance['notice'];

	//echo json_encode($instance);
	Zib_CFSwidget::echo_before($instance, 'mb20');
	zib_notice($links);
	Zib_CFSwidget::echo_after($instance);
}

 */

/////链接列表-------------------------------
class widget_ui_links_lists_2 extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_links_lists_2',
			'w_name'     =>  _name('链接列表(新版)'),
			'classname'     => '',
			'description'       => '速插入链接列表，很适合做友情链接，新版快链接列表模块，通过后台统一管理链接',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}
	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'more_but' => '<i class="fa fa-angle-right fa-fw"></i>更多',
			'more_but_url' => '',
			'in_affix' => '',
			'show_box'   => '',
			'type'   => 'all',
			'blank'  => '',
			'go_link'  => '',
			'alignment'   => '',
			'links_cats'   => '',
			'links_orderby'   => 'name',
			'links_order'   => 'ASC',
			'links_limit'   => '-1',
			'null'   => '',
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$page_input[] = array(
			'name' => __('标题：', 'zib_language'),
			'id' => $this->get_field_name('title'),
			'std' => $instance['title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('副标题：', 'zib_language'),
			'id' => $this->get_field_name('mini_title'),
			'std' => $instance['mini_title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->文案：', 'zib_language'),
			'id' => $this->get_field_name('more_but'),
			'std' => $instance['more_but'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->链接：', 'zib_language'),
			'id' => $this->get_field_name('more_but_url'),
			'std' => $instance['more_but_url'],
			'desc' => '设置为任意链接',
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);

		echo zib_edit_input_construct($page_input);

	?>
		<p>
			快速插入链接列表，你可搭配是否显示链接图片、简介等，但请注意统一性
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>

		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['show_box'], 'on'); ?> id="<?php echo $this->get_field_id('show_box'); ?>" name="<?php echo $this->get_field_name('show_box'); ?>"> 显示框架盒子
			</label>
		</p>

		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['go_link'], 'on'); ?> id="<?php echo $this->get_field_id('go_link'); ?>" name="<?php echo $this->get_field_name('go_link'); ?>"> 链接重定向<a class="" title="将非本站链接转为本站链接，有利于SEO"> ？</a>
			</label>
		</p>

		<p>
			<label>
				对齐方式：
				<select style="width:100%;" name="<?php echo $this->get_field_name('alignment'); ?>">
					<option value="" <?php selected('', $instance['alignment']); ?>>靠左</option>
					<option value="center" <?php selected('center', $instance['alignment']); ?>>居中</option>
					<option value="right" <?php selected('right', $instance['alignment']); ?>>靠右</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				显示模式：
				<select style="width:100%;" name="<?php echo $this->get_field_name('type'); ?>">
					<option value="card" <?php selected('card', $instance['type']); ?>>图文模式</option>
					<option value="image" <?php selected('image', $instance['type']); ?>>图片模式</option>
					<option value="simple" <?php selected('simple', $instance['type']); ?>>极简模式</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				选择链接分类：
				<select style="width:100%;" name="<?php echo $this->get_field_name('links_cats'); ?>">
					<option value="" <?php selected('card', $instance['type']); ?>>未选择</option>

					<?php
					$options_linkcats_obj = get_terms('link_category');
					foreach ($options_linkcats_obj as $tag) {
						$options_linkcats[$tag->term_id] = $tag->name;
						echo '<option value="' . $tag->term_id . '" ' . selected($tag->term_id, $instance['links_cats'], false) . '>' . $tag->name . '</option>';
					}

					?>
				</select>
				<span style="margin-bottom: 3px;color: #047aea;">请在后台-链接-添加链接以及链接分类</span>
			</label>
		</p>
		<p>
			<label>
				排序方式：
				<select style="width:100%;" name="<?php echo $this->get_field_name('links_orderby'); ?>">
					<option value="name" <?php selected('name', $instance['links_orderby']); ?>>名称排序</option>
					<option value="updated" <?php selected('updated', $instance['links_orderby']); ?>>更新时间</option>
					<option value="rating" <?php selected('rating', $instance['links_orderby']); ?>>链接评分</option>
					<option value="rand" <?php selected('rand', $instance['links_orderby']); ?>>随机排序</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				升序倒序：
				<select style="width:100%;" name="<?php echo $this->get_field_name('links_order'); ?>">
					<option value="ASC" <?php selected('ASC', $instance['links_order']); ?>>升序</option>
					<option value="DESC" <?php selected('DESC', $instance['links_order']); ?>>倒序</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				最大显示数量（填-1则为全部显示）：
				<input style="width:100%;" id="<?php echo $this->get_field_id('links_limit');
												?>" name="<?php echo $this->get_field_name('links_limit');
															?>" type="text" value="<?php echo $instance['links_limit'];
																					?>" />
			</label>
		</p>
	<?php
	}

	function widget($args, $instance)
	{

		extract($args);

		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'more_but' => '<i class="fa fa-angle-right fa-fw"></i>更多',
			'more_but_url' => '',
			'alignment'   => '',
			'show_box'   => '',
			'go_link'  => '',
			'in_affix' => '',
			'type'   => 'card',
			'links_cats'   => '',
			'links_orderby'   => 'name',
			'links_order'   => 'ASC',
			'links_limit'   => '-1',
			'blank'  => '',
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		$mini_title = $instance['mini_title'];
		if ($mini_title) {
			$mini_title = '<small class="ml10">' . $mini_title . '</small>';
		}
		$title = $instance['title'];
		$more_but = '';
		if ($instance['more_but'] && $instance['more_but_url']) {
			$more_but = '<div class="pull-right em09 mt3"><a href="' . $instance['more_but_url'] . '" class="muted-2-color">' . $instance['more_but'] . '</a></div>';
		}
		$mini_title .= $more_but;

		if ($title) {
			$title = '<div class="box-body notop"><div class="title-theme">' . $title . $mini_title . '</div></div>';
		}
		$links = array();

		$links_args = array(
			'orderby'        => $instance['links_orderby'], //排序方式
			'order'          => $instance['links_order'], //升序还是降序
			'limit'          => $instance['links_limit'], //最多显示数量
			'category'       => $instance['links_cats'], //以逗号分隔的类别ID列表
		);
		$links = get_bookmarks($links_args);

		$class = $instance['alignment'] ? ' text-' . $instance['alignment'] : '';

		$in_affix = $instance['in_affix'] ? ' data-affix="true"' : '';
		echo '<div' . $in_affix . ' class="theme-box' . $class . '">';

		echo $title;

		if ($instance['show_box']) {
			echo '<div class="links-lists zib-widget">';
		}
		if ($instance['links_cats'] && $links) {
			zib_links_box($links, $instance['type'], true, $instance['go_link']);
		}
		if ($instance['show_box']) {
			echo '</div>';
		}
		echo '</div>';

		//echo '<pre>'.json_encode($instance).'</pre>';
	?>

	<?php
	}
}

class widget_ui_new_comment extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_new_comment',
			'w_name'     =>  _name('最近评论'),
			'classname'     => '',
			'description'       => '显示网友最新的评论，建议显示在侧边栏',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}

	function widget($args, $instance)
	{
		extract($args);
		$defaults = array(
			'title' => '',
			'in_affix' => '',
			'mini_title' => '',
			'more_but' => '<i class="fa fa-angle-right fa-fw"></i>更多',
			'more_but_url' => '',
			'limit' => 8,
			'outer' => '1',
			'outpost' => ''
		);

		$instance = wp_parse_args((array) $instance, $defaults);

		$in_affix = $instance['in_affix'] ? ' data-affix="true"' : '';
		echo '<div' . $in_affix . ' class="theme-box">';
		$title = apply_filters('zib_widget_title', $instance);
		echo $title;

		echo '<div class="box-body comment-mini-lists zib-widget">';
		zib_widget_comments($instance['limit'], $instance['outpost'], $instance['outer']);
		echo '</div>';
		echo '</div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'in_affix' => '',
			'mini_title' => '',
			'more_but' => '<i class="fa fa-angle-right fa-fw"></i>更多',
			'more_but_url' => '',
			'limit' => 8,
			'outer' => '1',
			'outpost' => ''
		);
		$instance = wp_parse_args((array) $instance, $defaults);

		$page_input[] = array(
			'name' => __('标题：', 'zib_language'),
			'id' => $this->get_field_name('title'),
			'std' => $instance['title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('副标题：', 'zib_language'),
			'id' => $this->get_field_name('mini_title'),
			'std' => $instance['mini_title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->文案：', 'zib_language'),
			'id' => $this->get_field_name('more_but'),
			'std' => $instance['more_but'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->链接：', 'zib_language'),
			'id' => $this->get_field_name('more_but_url'),
			'std' => $instance['more_but_url'],
			'desc' => '设置为任意链接',
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);

		echo zib_edit_input_construct($page_input);

	?>

		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
			</label>
		</p>
		<p>
			<?php zib_user_help('排除某用户ID') ?>
			<label>
				<input class="widefat" id="<?php echo $this->get_field_id('outer'); ?>" name="<?php echo $this->get_field_name('outer'); ?>" type="text" value="<?php echo $instance['outer']; ?>" />
			</label>
		</p>
		<p>
			<label>
				排除某文章ID：
				<input class="widefat" id="<?php echo $this->get_field_id('outpost'); ?>" name="<?php echo $this->get_field_name('outpost'); ?>" type="number" value="<?php echo $instance['outpost']; ?>" />
			</label>
		</p>
	<?php
	}
}

class widget_ui_posts_navs extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_posts_navs',
			'w_name'     =>  _name('文章目录树'),
			'classname'     => '',
			'description'       => '显示文章的目录树，非文章页则不显示内容',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}

	function widget($args, $instance)
	{
		extract($args);
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		$mini_title = $instance['mini_title'];
		if ($mini_title) {
			$mini_title = '<small class="ml10">' . $mini_title . '</small>';
		}
		$title = esc_html($instance['title']) . esc_html($mini_title);
		if ($title) {
			$title = ' data-title="' . $title . '"';
		}
		$in_affix = $instance['in_affix'] ? ' data-affix="true"' : '';

		echo '<div' . $in_affix . ' class="posts-nav-box"' . $title . '></div>';
	}

	function form($instance)
	{
		$defaults = array(
			'title' => '文章目录',
			'in_affix' => '',
			'mini_title' => '',
		);
		$instance = wp_parse_args((array) $instance, $defaults);

	?>
		<p>
			<label>
				<i style="width:100%;color:#f80;">显示文章的目录，添加在非文章页则不会显示任何内容。在实时预览添加此模块时，请注意查看是否在文章页</i>
			</label>
		</p>
		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title');
												?>" name="<?php echo $this->get_field_name('title');
															?>" type="text" value="<?php echo $instance['title'];
																					?>" />
			</label>
		</p>
		<p>
			<label>
				副标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('mini_title');
												?>" name="<?php echo $this->get_field_name('mini_title');
															?>" type="text" value="<?php echo $instance['mini_title'];
																					?>" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked($instance['in_affix'], 'on'); ?> id="<?php echo $this->get_field_id('in_affix'); ?>" name="<?php echo $this->get_field_name('in_affix'); ?>"> 侧栏随动（仅在侧边栏有效）
			</label>
		</p>
<?php
	}
}


/////----- //一言//------ //一言//------ //一言//------ //一言//------ //一言//----
/////----- //一言//------ //一言//------ //一言//------ //一言//------ //一言//----
/////----- //一言//------ //一言//------ //一言//------ //一言//------ //一言//----
/////----- //一言//------ //一言//------ //一言//------ //一言//------ //一言//----
class widget_ui_yiyan extends WP_Widget
{
	function __construct()
	{
		$widget   = array(
			'w_id'     =>  'widget_ui_yiyan',
			'w_name'     =>  _name('一言'),
			'classname'     => 'yiyan-box main-bg theme-box text-center box-body radius8 main-shadow',
			'description'       => '这是一个显示一言的小工具，每次页面刷新或者每隔30秒会自动更新内容',
		);
		parent::__construct($widget['w_id'], $widget['w_name'], $widget);
	}

	function widget($args, $instance)
	{
		extract($args);
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'mini_title' => '',
			'more_but' => '<i class="fa fa-angle-right fa-fw"></i>更多',
		);

		$instance = wp_parse_args((array) $instance, $defaults);

		$in_affix = $instance['in_affix'] ? ' data-affix="true"' : '';
		$title = apply_filters('zib_widget_title', $instance);

		echo '<div' . $in_affix . ' class="theme-box">';
		echo $title;
		echo '<div class="yiyan-box main-bg text-center box-body radius8 main-shadow">';
		echo '<div class="yiyan"></div>';
		echo '</div>';
		echo '</div>';
	}
	function form($instance)
	{
		$defaults = array(
			'title' => '',
			'mini_title' => '',
			'in_affix' => '',
			'more_but_url' => '',
			'mini_title' => '',
			'more_but' => '<i class="fa fa-angle-right fa-fw"></i>更多',
		);
		$instance = wp_parse_args((array) $instance, $defaults);

		$page_input[] = array(
			'name' => __('标题：', 'zib_language'),
			'id' => $this->get_field_name('title'),
			'std' => $instance['title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('副标题：', 'zib_language'),
			'id' => $this->get_field_name('mini_title'),
			'std' => $instance['mini_title'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->文案：', 'zib_language'),
			'id' => $this->get_field_name('more_but'),
			'std' => $instance['more_but'],
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'name' => __('标题右侧按钮->链接：', 'zib_language'),
			'id' => $this->get_field_name('more_but_url'),
			'std' => $instance['more_but_url'],
			'desc' => '设置为任意链接',
			'style' => 'margin: 10px auto;',
			'type' => 'text',
		);
		$page_input[] = array(
			'id' => $this->get_field_name('in_affix'),
			'std' => $instance['in_affix'],
			'desc' => '侧栏随动（仅在侧边栏有效）',
			'style' => 'margin: 15px auto;',
			'type' => 'checkbox',
		);
		echo zib_edit_input_construct($page_input);
	}
}
