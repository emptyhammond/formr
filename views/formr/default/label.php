<?php
echo Form::label($column['column_name'], isset($options['labels'][$column['column_name']]) ? $options['labels'][$column['column_name']] : ucwords($column['column_name'])));