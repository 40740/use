<?php
/*
订单中心
*/
if (!defined('ABSPATH')) {
     exit;
}
$user_Info   = wp_get_current_user();
if (!is_user_logged_in()) {
     exit;
}

$order_url = admin_url('admin.php?page=zibpay_order_page');
$desc_url = $order_url;
$s = !empty($_REQUEST['s']) ? $_REQUEST['s'] : false;

$WHERE = '';

if ($s) {
     $WHERE = "WHERE
     `pay_num` LIKE '%$s%' OR
     `order_num` LIKE '%$s%' OR
     `other` LIKE '%$s%' OR
     `user_id` LIKE '%$s%' OR
     `post_id` LIKE '%$s%'";
     $desc_url = $order_url . '&amp;s=' . $s;
} else {
}

$WHERE_status = !empty($_GET['status']) ? $_GET['status'] : false;
if ($WHERE_status) {
     $WHERE = "WHERE
     `status` = $WHERE_status";
     $desc_url = $order_url . '&amp;status=' . $WHERE_status;
}
$WHERE_order_type = !empty($_GET['order_type']) ? $_GET['order_type'] : false;
if ($WHERE_order_type) {
     $WHERE = "WHERE
     `order_type` = $WHERE_order_type";
     $desc_url = $order_url . '&amp;order_type=' . $WHERE_order_type;
}
if (!empty($_GET['delete'])) {
     $delete_num = $_GET['delete'];
     $result = ZibPay::delete_order($delete_num);
     if ($result) {
          echo '<div class="updated notice-alt"><h4 style="color: #0aaf19;">删除成功[订单号：' . $delete_num . ']</h4></div>';
     }
}
if (!empty($_GET['action']) && $_GET['action'] == 'clear_order') {
     $result = ZibPay::clear_order(14);
     if ($result) {
          echo '<div class="updated notice-alt"><h4 style="color: #0aaf19;">清理完成[共清理：' . $result . '个订单]</h4></div>';
     } else {
          echo '<div class="updated notice-alt"><h4 style="color: #0aaf19;">没有需要清理的订单</h4></div>';
     }
}
global $wpdb;
//统计数据
$total_trade   = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order $WHERE"); 

//分页计算
$ice_perpage = 20;
$pages = ceil($total_trade / $ice_perpage);
$page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$offset = $ice_perpage * ($page - 1);
$order = !empty($_GET['orderby']) ? $_GET['orderby'] : 'pay_time';
$desc = !empty($_GET['desc']) ? $_GET['desc'] : 'DESC';

$list = $wpdb->get_results("SELECT * FROM $wpdb->zibpay_order $WHERE order by $order $desc limit $offset,$ice_perpage");

//echo  json_encode($list);
//echo "SELECT * FROM $wpdb->zibpay_order $WHERE order by $order $desc limit $offset,$ice_perpage";

$all_c   = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order");
$paid_c  = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE `status` = 1");
$all_1_c   = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE `order_type` = 1");
$all_2_c   = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE `order_type` = 2");
$all_4_c   = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->zibpay_order WHERE `order_type` = 4");

?>
<div class="wrap">
     <h2>全部订单</h2>

     <div class="order-header">
          <ul class="subsubsub">
               <li class=""><a class="" href="<?php echo $order_url; ?>">全部订单</a>(<?php echo $all_c ?>)</li> |
               <li class=""><a class="" href="<?php echo $order_url . '&amp;status=1'; ?>">支付订单</a>(<?php echo $paid_c ?>)</li> |
               <li class=""><a class="" href="<?php echo $order_url . '&amp;order_type=1'; ?>">付费阅读</a>(<?php echo $all_1_c ?>)</li> |
               <li class=""><a class="" href="<?php echo $order_url . '&amp;order_type=2'; ?>">付费资源</a>(<?php echo $all_2_c ?>)</li>
               <li class=""><a class="" href="<?php echo $order_url . '&amp;order_type=4'; ?>">购买会员</a>(<?php echo $all_4_c ?>)</li>
          </ul>

          <form class="form-inline form-order" method="post" action="<?php echo $order_url; ?>">
               <div class="form-group">
                    <input type="text" class="form-control" name="s" placeholder="搜索订单">
                    <button type="submit" class="button button-primary">提交</button>
               </div>
          </form>
          <div class="order-header">
               <a class="button" onclick="return confirm('清理订单会删除2周前所有未支付的订单，不可恢复！确认清理订单？')" href="<?php echo $order_url . '&amp;action=clear_order'; ?>">清理订单</a>
          </div>
          <?php echo $s ? '<div class="">"' . esc_attr($s) . '" 的搜索结果</div>' : ''; ?>
     </div>

     <div class="table-box">
          <table class="widefat fixed striped posts">
               <thead>
                    <tr>
                         <?php

                         $theads = array();
                         $theads[] = array('width' => '2%', 'orderby' => 'id', 'name' => 'ID');
                         $theads[] = array('width' => '8%', 'orderby' => 'post_id', 'name' => '商品');
                         $theads[] = array('width' => '3%', 'orderby' => 'user_id', 'name' => '用户');
                         // $theads[] = array('width' => '3%', 'type' => 'ip_address', 'name' => '用户IP地址');
                         $theads[] = array('width' => '3%', 'orderby' => 'product_id', 'name' => '商品ID');
                         $theads[] = array('width' => '5%', 'orderby' => 'order_num', 'name' => '订单号');
                         $theads[] = array('width' => '3%', 'orderby' => 'order_price', 'name' => '订单价格');
                         $theads[] = array('width' => '3%', 'orderby' => 'order_type', 'name' => '订单类型');
                         $theads[] = array('width' => '5%', 'orderby' => 'create_time', 'name' => '创建时间');
                         $theads[] = array('width' => '5%', 'orderby' => 'pay_num', 'name' => '支付订单');
                         $theads[] = array('width' => '3%', 'orderby' => 'pay_type', 'name' => '支付类型');
                         $theads[] = array('width' => '3%', 'orderby' => 'pay_price', 'name' => '支付金额');
                         $theads[] = array('width' => '3%', 'orderby' => 'pay_time', 'name' => '支付时间');
                         $theads[] = array('width' => '3%', 'orderby' => 'status', 'name' => '订单状态');

                         foreach ($theads as $thead) {
                              $orderby = '';
                              if ($thead['orderby']) {
                                   $orderby_url = add_query_arg('orderby', $thead['orderby'], $desc_url);
                                   $orderby .= '<a title="降序" href="' . add_query_arg('desc', 'ASC', $orderby_url) . '"><span class="dashicons dashicons-arrow-up"></span></a>';
                                   $orderby .= '<a title="升序" href="' . add_query_arg('desc', 'DESC', $orderby_url) . '"><span class="dashicons dashicons-arrow-down"></span></a>';
                                   $orderby = '<span class="orderby-but">' . $orderby . '</span>';
                              }
                              echo '<th class="" width="' . $thead['width'] . '">' . $thead['name'] . $orderby . '</th>';
                         } ?>
                    </tr>
               </thead>
               <tbody>
                    <?php
                    if ($list) {
                         $ii = 1;
                         foreach ($list as $value) {

                              $edit = '<a class="" onclick="return confirm(\'确认删除此订单?  删除后数据不可恢复!\')" href="' . $order_url . '&amp;delete=' . $value->order_num . '">删除</a>';
                              $status = $value->status ? '已支付' : '未支付';

                              $order_type = zibpay_get_pay_type_name($value->order_type);
                              $user_a = '<a target="_blank" href="' . get_author_posts_url($value->user_id) . '">' . get_the_author_meta('display_name', $value->user_id) . '</a>';
                              $link = get_edit_post_link($value->post_id);
                              if ($link) {
                                   $post_a = '<a style=" overflow: hidden; text-overflow:ellipsis; white-space: nowrap; display: block; " target="_blank" href="' . $link . '">' . get_the_title($value->post_id) . '</a>';
                              } else {
                                   $post_a = $order_type;
                              }
                              echo "<tr>\n";
                              echo "<td>$value->id<div class='row-actions'>$edit</div></td>\n";

                              echo "<td>$post_a</td>\n";
                              echo "<td>$user_a</td>\n";

                              //echo "<td>$value->ip_address</td>\n";
                              echo "<td>$value->product_id</td>\n";
                              echo "<td>$value->order_num</td>\n";
                              echo "<td>$value->order_price</td>\n";
                              echo "<td>$order_type</td>\n";
                              echo "<td>$value->create_time</td>\n";
                              echo "<td>$value->pay_num</td>\n";
                              echo "<td>$value->pay_type</td>\n";
                              echo "<td>$value->pay_price</td>\n";
                              echo "<td>$value->pay_time</td>\n";
                              echo "<td>$status</td>\n";

                              echo "</tr>";
                              $ii++;
                         }
                    } else {
                         echo '<tr><td colspan="12" align="center"><strong>暂无订单</strong></td></tr>';
                    }
                    ?>
               </tbody>
          </table>
     </div>
     <?php zibpay_admin_pagenavi($total_trade, $ice_perpage); ?>
     　　
</div>