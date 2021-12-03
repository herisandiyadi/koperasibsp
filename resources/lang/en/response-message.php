<?php

return [
    'success'=> [
        'created'=> 'New :object has been created.',
        'updated'=> ':object has been saved',
        'deleted'=> ':object has been deleted',
        'upload'=> ':object has been uploaded.'

    ],
    'failed'=>[
        'created'=> 'Failed to create new :object',
        'updated'=> 'Failed to update :object',
        'deleted'=> 'Failed to to delete :object, check if the data still in used',
        'upload'=> 'Failed to upload :object.'
    ],
    'confirmation'=> [
        'simple'=> 'Are you sure?',
        'create'=> 'Are you sure want to create :object ?',
        'update'=> 'Are you sure want to save this change/s on :object ?',
        'delete'=> 'Are you sure want to delete :object ?',
        'application'=> 'Are you sure want to apply :object ?'
    ],
    'unauthorized'=> [
        'visit'=> 'Restricted page',
        'feature'=> 'You are not authorize to access this feature',
        'session'=> 'Your session has expired. Please login to continue.'
    ],
    'notfound'=> [
        'url'=> 'We cant find the url that you looking for',
        'page'=> 'We cant find page that you looking for',
        'db'=> 'We cant find data/record that you looking for.',
        'file'=> 'We cant find file that you looking for'
    ]
];