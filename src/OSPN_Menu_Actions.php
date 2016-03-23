<?php

namespace OSPN;
use OSPN\Form\OSPN_Podcast_Form;
use OSPN\Table\OSPN_Podcasts_Table;

/**
 * Class OSPN_MenuActions
 * @package OSPN
 */
class OSPN_Menu_Actions extends OSPN_Base
{
    /**
     *
     */
    public function podcasts() {
        /** @global OSPN_Podcasts_Table $ospn_podcasts_table */
        global $ospn_podcasts_table;
        $ospn_podcasts_table = new OSPN_Podcasts_Table();
    }

    /**
     *
     */
    public function podcast_edit() {
        global $wpdb;

        /** @global OSPN_Podcast_Form $podcast_form */
        global $podcast_form;

        /** @var int $podcast */
        $podcast = $_REQUEST['podcast'];

        /** @var object $p */
        $p = $wpdb->get_row(<<<TAG
SELECT
	p.*
FROM
	{$wpdb->prefix}ospn_podcasts p
WHERE
	p.blog_id = {$podcast};
TAG
);
        $podcast_form = new OSPN_Podcast_Form();
        $podcast_form->blog_id = $p->blog_id;
        $podcast_form->podcast_name = $p->podcast_name;
        $podcast_form->website = $p->website;
        $podcast_form->contact = $p->contact;
        $podcast_form->podcast_feed = $p->podcast_feed;
        $podcast_form->active = $p->active;

        /** @var object $h */
        $h = $wpdb->get_results(<<<TAG
SELECT
    h.*
FROM
    {$wpdb->prefix}ospn_podcast_hosts h
WHERE
    h.podcast_id = {$podcast};
TAG
);
        $podcast_form->host_id = $h[0]->host_id;
        if (sizeof($h) > 1) {
            $podcast_form->host2_id = $h[1]->host_id;
        }
    }

}