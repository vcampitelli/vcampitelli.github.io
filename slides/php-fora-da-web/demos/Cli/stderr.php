<?php
// fputs(STDERR, 'Erro no sistema');
stream_filter_append(STDERR, 'string.toupper');
stream_copy_to_stream(STDIN, STDERR);
