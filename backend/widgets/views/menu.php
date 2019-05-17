
<!-- =============================================== -->

<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" >

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <?php foreach($menu as $vo){?>
                <li class="treeview  <?= in_array($vo['id'],$up_node)?' active menu-open':''?>">
                    <a href="#">
                        <i class="fa <?=$vo['icon']?$vo['icon']:'fa-dashboard'?>"></i> <span><?=$vo['name']?></span>
                        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php foreach($vo['linkNode'] as $item){?>
                            <li class="<?= empty($item['linkNode'])?'':'treeview'?>  <?= in_array($item['id'],$up_node)?' active menu-open':''?>">

                                <a href="<?= $item['uri']?\yii\helpers\Url::to([$item['uri']]):'#'?>">
                                    <i class="fa fa-circle-o"></i> <?=$item['name']?>
                                    <?php if(!empty($item['linkNode'])){?>
                                        <span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                    <?php } ?>
                                </a>
                                <?php if(!empty($item['linkNode'])){?>

                                    <ul class="treeview-menu">
                                        <?php foreach($item['linkNode'] as $deep){?>
                                            <li class="<?= in_array($deep['id'],$up_node)?' active menu-open':''?>"><a href="<?= $deep['uri']?\yii\helpers\Url::to([$deep['uri']]):'#'?>"><i class="fa fa-circle-o"></i> <?=$deep['name']?></a></li>
                                        <?php } ?>

                                    </ul>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- =============================================== -->

