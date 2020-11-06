<?php

if (!function_exists('cl_parse_url')) {

    function cl_parse_url($url)
    {
        // https://res.cloudinary.com/<cloud_name>/<resource_type>/<type>/<transformations>/<version>/<public_id>.<format>
        $struct = [
            'cloud_name' => null,
            'resource_type' => 'image', // optional
            'type' => 'upload', // optional
            'transformations' => [], // optional
            'version' => null, // optional
            'public_id' => null, // will not include extension, but WILL include folders if any: FOLDER1/FOLDER2/file
            'format' => null
        ];

        $url_parts = parse_url($url);
        $path_parts = pathinfo($url_parts['path']);

        $path_segments = array_values(array_filter(explode('/', $path_parts['dirname'])));
        $path_segment_count = count($path_segments);

        $struct['cloud_name'] = $path_segments[0];

        // https://cloudinary.com/documentation/image_transformations#delivery_types
        $delivery_types = ['upload', 'private', 'authenticated', 'fetch', 'facebook', 'twitter', 'twitter_name', 'gravatar'];

        $cursor = 1;

        if ($path_segment_count > 1 && in_array($path_segments[1], ['image', 'raw', 'video'])) {
            $struct['resource_type'] = $path_segments[1];

            // delivery_type MUST be present if resource_type was present
            if (in_array($path_segments[2], $delivery_types)) {
                $struct['type'] = $path_segments[2];
            }

            $cursor = 3;
        }

        $trans_types = array_map(function ($val) {
            return $val . '_';
        }, explode(' ', 'w h c ar g z x y f q r a e o bo b l u d dl co cs dpr pg dn fl t if fn $'));

        $transformations = [];

        for ($i = $cursor; $i < count($path_segments); $i++) {
            $temp = $path_segments[$i];

            foreach ($trans_types as $type) {

                // must be a transformation option!
                if (strpos($temp, $type) === 0) {
                    $transformations[] = $temp;

                    $cursor++;
                    continue;
                }
            }
        }

        $struct['transformations'] = implode('/', $transformations);

        if (!empty($path_segments[$cursor]) && strpos($path_segments[$cursor], 'v') === 0) {
            $struct['version'] = intval(substr($path_segments[$cursor], 1));
            $cursor++;
        }

        $folder_segments = array_slice($path_segments, $cursor);

        if (!empty($path_parts['filename'])) {

            if ($folder_segments) {
                $struct['public_id'] = implode('/', $folder_segments) . '/' . $path_parts['filename'];
            } else {
                $struct['public_id'] = $path_parts['filename'];
            }
        }

        if (!empty($path_parts['extension'])) {
            $struct['format'] = $path_parts['extension'];
        }

        return $struct;
    }
}

if (!function_exists('cl_build_url')) {

    function cl_build_url($options)
    {
        // https://res.cloudinary.com/<cloud_name>/<resource_type>/<type>/<transformations>/<version>/<public_id>.<format>
        $url = sprintf('https://res.cloudinary.com/%s/%s/%s/',
            $options['cloud_name'],
            $options['resource_type'],
            $options['type']
        );

        if (!empty($options['transformations'])) {
            $url .= $options['transformations'] . '/';
        }

        if (!empty($options['version'])) {
            $url .= 'v' . $options['version'] . '/';
        }

        $url .= $options['public_id'];

        if (!empty($options['format'])) {
            $url .= '.' . $options['format'];
        }

        return $url;
    }
}

if (!function_exists('cl_url_update_transformations')) {

    function cl_url_update_transformations($url, $transformations)
    {
        $parsed = cl_parse_url($url);

        // any existing?
        if ($parsed['transformations']) {
            $parsed['transformations'] .= ',' . $transformations;
        } else {
            $parsed['transformations'] = $transformations;
        }

        return cl_build_url($parsed);
    }
}