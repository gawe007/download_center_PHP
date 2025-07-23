<!DOCTYPE html>
<html lang="EN">
<head>
  <meta charset='utf-8'>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="https://github.com/gawe007">
  <meta name="description" content="Download Center by Gawe007 built for Teknik Univpancasila">

    <!-- ===============================================--><!--    Document Title--><!-- ===============================================-->
    <title>Download Center - Teknik Univpancasila</title>
    <meta name="theme-color" content="#fafafa">

    <!-- ===============================================--><!--    Stylesheets--><!-- ===============================================-->
    <link href="<?= htmlspecialchars($global['full_url']) ?>/theme/style/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars($global['full_url']) ?>/theme/style/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($global['full_url']) ?>/theme/style/dataTables.dataTables.css" />
    <link href="<?= htmlspecialchars($global['full_url']) ?>/theme/style/filepond.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($global['full_url']) ?>/theme/style/filepond-plugin-image-preview.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($global['full_url']) ?>/theme/style/filepond.custom.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($global['full_url']) ?>/theme/style/sweetalert2.min.css" rel="stylesheet">
    <link href="<?= htmlspecialchars($global['full_url']) ?>/theme/style/tagify.css" rel="stylesheet" type="text/css" />
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/jquery-3.7.1.min.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/bootstrap.bundle.min.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/sweetalert2.all.min.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/dataTables.min.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/filepond-plugin-file-validate-type.min.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/filepond-plugin-image-preview.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/filepond-plugin-file-validate-size.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/filepond-plugin-image-validate-size.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/filepond.min.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/filepond.jquery.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/tagify.js"></script>
    <script src="<?= htmlspecialchars($global['full_url']) ?>/theme/script/tagify.polyfills.min.js"></script>
    <style>
      a.link-footer {
        color: white;
      }
      
      .tag-manual {
        background: #eee;
        border-radius: 4px;
        padding: 2px 6px;
        margin: 0 2px;
        display: inline-block;
        font-size: 0.85em;
        line-height: 1em;
        cursor: pointer;
      }

      .tag-manual:hover{
        background-color: #bdbdbd;
      }

      #filesTable tbody tr {
        cursor: pointer;
        transition: background-color 0.2s ease;
      }

      #filesTable tbody tr:hover {
        background-color: #f0f0f0;
      }

    </style>
  </head>

  <body>