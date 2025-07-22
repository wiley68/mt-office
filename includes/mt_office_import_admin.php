<h2>Настройки на MT Office</h2>

<form method="post">
    <?php wp_nonce_field('mt_office_export'); ?>
    <input
        type="hidden"
        name="mt_office_action"
        value="export"
    >
    <input
        type="submit"
        class="button button-primary"
        value="Експорт на таблицата"
    >
</form>

<br><br>

<form
    method="post"
    enctype="multipart/form-data"
>
    <?php wp_nonce_field('mt_office_import'); ?>
    <input
        type="hidden"
        name="mt_office_action"
        value="import"
    >
    <input
        type="file"
        name="sql_file"
        accept=".sql"
        required
    >
    <input
        type="submit"
        class="button button-secondary"
        value="Импорт и възстановяване"
    >
</form>