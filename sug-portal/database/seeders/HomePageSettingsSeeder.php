<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class HomePageSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            'site_name' => 'Student Union Government',

            // Hero Section
            'home_hero_title' => 'Empowering Students, Building the Future.',
            'home_hero_subtitle' => 'The official digital hub for the Student Union Government. Manage your dues, stay updated with campus news, and exercise your democratic right to vote.',
            'home_hero_cta_primary' => 'Get Started',
            'home_hero_cta_secondary' => 'Learn More',

            // Services Section
            'home_services_title' => 'Our Student Services',
            'home_services_subtitle' => 'Everything you need to navigate your university life seamlessly in one place.',

            // Feature: Fees
            'home_feature_fees_title' => 'Fee Management',
            'home_feature_fees_desc' => 'Quickly pay your SUG dues and download verified receipts instantly.',
            'home_feature_fees_cta' => 'Pay Now',

            // Feature: Voting
            'home_feature_voting_title' => 'Online Voting',
            'home_feature_voting_desc' => 'Participate in SUG elections securely and anonymously from your device.',
            'home_feature_voting_cta' => 'Vote Now',

            // Feature: News
            'home_feature_news_title' => 'Campus News',
            'home_feature_news_desc' => 'Stay informed with the latest announcements, news, and official events.',
            'home_feature_news_cta' => 'View News',

            // Updates Section
            'home_updates_title' => 'Latest Campus Updates',
            'home_updates_subtitle' => 'Keep up to date with what\'s happening on campus.',
            'home_updates_cta' => 'View All News',

            // Footer
            'home_footer_description' => 'The official Student Union Government platform dedicated to enhancing the student experience through digitalization, transparency, and accessibility.',
            'home_footer_links_title' => 'Quick Links',
            'home_footer_support_title' => 'Student Support',
            'footer_copyright' => 'All rights reserved.',

            // Navigation
            'nav_home' => 'Home',
            'nav_about' => 'About',
            'nav_news' => 'News',
            'nav_events' => 'Events',
            'nav_contact' => 'Contact',
            'nav_dashboard' => 'Dashboard',
            'nav_logout' => 'Logout',
            'nav_login' => 'Log in',
            'nav_register' => 'Register',

            // Footer Links
            'footer_about_link' => 'About SUG',
            'footer_news_link' => 'Campus News',
            'footer_events_link' => 'Official Events',
            'footer_contact_link' => 'Contact Us',
            'footer_login_link' => 'Student Login',
            'footer_register_link' => 'Registration',
            'footer_verify_link' => 'Verify Receipt',

            // UI Labels
            'label_read_more' => 'Read More',
            'label_no_news' => 'No recent news available.',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
