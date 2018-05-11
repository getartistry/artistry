<?php ob_start(); ?>
<label for="p_asp_post_type">Post type</label>
<select name="p_asp_post_type">
    <?php foreach($post_types as $post_type): ?>
        <option value="<?php echo $post_type ?>"><?php echo $post_type ?></option>
    <?php endforeach; ?>
</select>
<label for="p_asp_blog">Blog</label>
<select name="p_asp_blog">
    <option value="0" selected>Current</option>
    <?php foreach($blogs as $blog): ?>
        <?php $blog_details = get_blog_details($blog['blog_id']); ?>
        <option value="<?php echo $blog['blog_id'] ?>"><?php echo  $blog_details->blogname; ?></option>
    <?php endforeach; ?>
</select>
<label for="p_asp_ordering">Ordering</label>
<select name="p_asp_ordering">
    <option value="id DESC" selected>ID descending</option>
    <option value="id ASC">ID ascending</option>
    <option value="title DESC">Title descending</option>
    <option value="title ASC">Title ascending</option>
    <option value="priority DESC">Priority descending</option>
    <option value="priority ASC">Priority ascending</option>
</select>

<div style="display: inline-block;">
    <label>Filter</label><input name="p_asp_filter" type="text" placeholder="post title here">
</div>

<label>Limit</label><input name="p_asp_limit" type="text" style="width: 40px;" value="20">

<input type='submit' id="p_asp_submit" class='submit' value='Filter'/>
<?php $_rr = ob_get_clean(); ?>

<?php if (ASP_DEMO): ?>
    <p class="infoMsg">DEMO MODE ENABLED - Please note, that these options are read-only</p>
<?php endif; ?>
<div class='wpdreams-slider'>
    <form name='asp_priorities' id="asp_priorities" method='post'>
        <fieldset>
            <legend>Filter Posts</legend>
            <?php print $_rr; ?>
        </fieldset>
    </form>
</div>

<div id="p_asp_loader"></div>
<div id="p_asp_results"><p style="text-align:center;">Click the filter to load results!</p></div>