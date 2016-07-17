<?php

use Illuminate\Database\Seeder;
use App\Country as CountryModel;
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        App\Country::truncate();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Afghanistan';
        $country->country_short_name ='AF';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Albania';
        $country->country_short_name ='AL';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Algeria';
        $country->country_short_name ='DZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'American Samoa';
        $country->country_short_name ='AS';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Andorra';
        $country->country_short_name ='AD';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Antigua & Barbuda';
        $country->country_short_name ='AG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Angola';
        $country->country_short_name ='AO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Anguilla';
        $country->country_short_name ='AI';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Antarctica';
        $country->country_short_name ='AR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Armenia';
        $country->country_short_name ='AM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Aruba';
        $country->country_short_name ='AW';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Australia';
        $country->country_short_name ='AU';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Austria';
        $country->country_short_name ='AT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Azerbaijan';
        $country->country_short_name ='AZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Bahamas';
        $country->country_short_name ='BS';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Bahrain';
        $country->country_short_name ='BH';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Bangladesh';
        $country->country_short_name ='BD';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Barbados';
        $country->country_short_name ='BB';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Belarus';
        $country->country_short_name ='BY';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Belgium';
        $country->country_short_name ='BE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Belize';
        $country->country_short_name ='BZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Benin';
        $country->country_short_name ='BJ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Bermuda';
        $country->country_short_name ='BM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Bhutan';
        $country->country_short_name ='BT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Bolivia';
        $country->country_short_name ='BO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Bosnia And Herzegovina';
        $country->country_short_name ='BA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Botswana';
        $country->country_short_name ='BW';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Bouvet Island';
        $country->country_short_name ='BV';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Brazil';
        $country->country_short_name ='BR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'British Indian Ocean Territory';
        $country->country_short_name ='IO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Brunei';
        $country->country_short_name ='BN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Bulgaria';
        $country->country_short_name ='BG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Burundi';
        $country->country_short_name ='BI';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Burkina Faso';
        $country->country_short_name ='BF';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Cambodia';
        $country->country_short_name ='KH';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Cameroon';
        $country->country_short_name ='CM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Canada';
        $country->country_short_name ='CA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Cape Verde';
        $country->country_short_name ='CV';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Cayman Islands';
        $country->country_short_name ='KY';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Central African Republic';
        $country->country_short_name ='CF';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Chad';
        $country->country_short_name ='TD';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Chile';
        $country->country_short_name ='CL';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'China';
        $country->country_short_name ='CN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Christmas Island';
        $country->country_short_name ='CX';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Cocos (Keeling) Islands';
        $country->country_short_name ='CC';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Colombia';
        $country->country_short_name ='CO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Comoros';
        $country->country_short_name ='KM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Congo';
        $country->country_short_name ='CG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Congo, The Democratic Republic Of The';
        $country->country_short_name ='CD';
        $country->save();

        /***/
        $country = new countryModel;
        $country->country_long_name = 'Cook Islands';
        $country->country_short_name ='CD';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Costa Rica';
        $country->country_short_name ='CR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Cote DIvoire';
        $country->country_short_name ='CI';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Croatia (Hrvatska)';
        $country->country_short_name ='HR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Cuba';
        $country->country_short_name ='CU';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Cyprus';
        $country->country_short_name ='CY';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Czech Republic';
        $country->country_short_name ='CZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Denmark';
        $country->country_short_name ='DK';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Djibouti';
        $country->country_short_name ='DJ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Dominica';
        $country->country_short_name ='DM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Dominican Republic';
        $country->country_short_name ='DO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'East Timor';
        $country->country_short_name ='TL';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Ecuador';
        $country->country_short_name ='EC';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Egypt';
        $country->country_short_name ='EG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'El Salvador';
        $country->country_short_name ='SV';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Equatorial Guinea';
        $country->country_short_name ='GQ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Eritrea';
        $country->country_short_name ='ER';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Estonia';
        $country->country_short_name ='EE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Ethiopia';
        $country->country_short_name ='ET';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Falkland Islands';
        $country->country_short_name ='AF';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Faroe Islands';
        $country->country_short_name ='FO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Fiji';
        $country->country_short_name ='FJ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Finland';
        $country->country_short_name ='FI';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'France';
        $country->country_short_name ='FR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'French Guiana';
        $country->country_short_name ='GF';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'French Polynesia';
        $country->country_short_name ='PF';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'French Southern Territories';
        $country->country_short_name ='TF';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Gabon';
        $country->country_short_name ='GA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Gambia';
        $country->country_short_name ='GM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Georgia';
        $country->country_short_name ='GE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Germany';
        $country->country_short_name ='DE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Ghana';
        $country->country_short_name ='GH';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Gibraltar';
        $country->country_short_name ='GI';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Greece';
        $country->country_short_name ='GR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Greenland';
        $country->country_short_name ='GL';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Grenada';
        $country->country_short_name ='GD';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Guadeloupe';
        $country->country_short_name ='GP';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Guam';
        $country->country_short_name ='GU';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Guatemala';
        $country->country_short_name ='GT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Guinea-Bissau';
        $country->country_short_name ='GW';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Guyana';
        $country->country_short_name ='GY';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Haiti';
        $country->country_short_name ='HT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Holy See';
        $country->country_short_name ='VA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Honduras';
        $country->country_short_name ='HN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Hong Kong';
        $country->country_short_name ='HK';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Hungary';
        $country->country_short_name ='HU';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Iceland';
        $country->country_short_name ='IS';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'India';
        $country->country_short_name ='IN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Indonesia';
        $country->country_short_name ='ID';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Iran';
        $country->country_short_name ='IR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Iraq';
        $country->country_short_name ='IQ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Ireland';
        $country->country_short_name ='IE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Israel';
        $country->country_short_name ='IL';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Italy';
        $country->country_short_name ='IT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Jamaica';
        $country->country_short_name ='JM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Japan';
        $country->country_short_name ='JP';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Jordan';
        $country->country_short_name ='JO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Kazakhstan';
        $country->country_short_name ='KZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Kenya';
        $country->country_short_name ='KE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Kiribati';
        $country->country_short_name ='KI';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Korea';
        $country->country_short_name ='KP';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'North Korea';
        $country->country_short_name ='KR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Kuwait';
        $country->country_short_name ='KW';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Kyrgyzstan';
        $country->country_short_name ='KG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Laos';
        $country->country_short_name ='LA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Latvia';
        $country->country_short_name ='LV';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Lebanon';
        $country->country_short_name ='LB';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Lesotho';
        $country->country_short_name ='LS';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Liberia';
        $country->country_short_name ='LR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Libyan Arab';
        $country->country_short_name ='LY';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Liechtenstein';
        $country->country_short_name ='LI';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Lithuania';
        $country->country_short_name ='LT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Luxembourg';
        $country->country_short_name ='LU';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Macau';
        $country->country_short_name ='MO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Macedonia';
        $country->country_short_name ='MK';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Madagascar';
        $country->country_short_name ='MG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Malawi';
        $country->country_short_name ='MW';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Malaysia';
        $country->country_short_name ='MY';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Maldives';
        $country->country_short_name ='MV';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Mali';
        $country->country_short_name ='ML';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Malta';
        $country->country_short_name ='MT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Marshall Islands';
        $country->country_short_name ='MH';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Martinique';
        $country->country_short_name ='MQ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Mauritania';
        $country->country_short_name ='MR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Mauritius';
        $country->country_short_name ='MU';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Mayotte';
        $country->country_short_name ='YT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Mexico';
        $country->country_short_name ='MX';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Micronesia';
        $country->country_short_name ='FM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Moldova';
        $country->country_short_name ='MD';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Monaco';
        $country->country_short_name ='MC';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Mongolia';
        $country->country_short_name ='MN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Montserrat';
        $country->country_short_name ='MS';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Morocco';
        $country->country_short_name ='MA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Mozambique';
        $country->country_short_name ='MZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Myanmar';
        $country->country_short_name ='MM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Namibia';
        $country->country_short_name ='NA';
        $country->save();

        /***/
        $country = new countryModel;
        $country->country_long_name = 'Nauru';
        $country->country_short_name ='NR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Nepal';
        $country->country_short_name ='NP';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Netherlands';
        $country->country_short_name ='NL';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Netherlands Antilles';
        $country->country_short_name ='AN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'New Caledonia';
        $country->country_short_name ='NC';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'New Zealand';
        $country->country_short_name ='NZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Nicaragua';
        $country->country_short_name ='NI';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Niger';
        $country->country_short_name ='NE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Nigeria';
        $country->country_short_name ='NG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Niue';
        $country->country_short_name ='NU';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Norfolk Island';
        $country->country_short_name ='NF';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Northern Mariana Islands';
        $country->country_short_name ='MP';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Norway';
        $country->country_short_name ='OM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Pakistan';
        $country->country_short_name ='PK';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Palau';
        $country->country_short_name ='PW';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Panama';
        $country->country_short_name ='PA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Papua New Guinea';
        $country->country_short_name ='PG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Paraguay';
        $country->country_short_name ='PY';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Peru';
        $country->country_short_name ='PE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Philippines';
        $country->country_short_name ='PH';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Pitcairn Islands';
        $country->country_short_name ='PN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Poland';
        $country->country_short_name ='PL';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Portugal';
        $country->country_short_name ='PT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Puerto Rico';
        $country->country_short_name ='PR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Qatar';
        $country->country_short_name ='QA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Reunion';
        $country->country_short_name ='RE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Romania';
        $country->country_short_name ='RO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Russia';
        $country->country_short_name ='RU';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Rwanda';
        $country->country_short_name ='RW';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Saint Kitts and Nevis';
        $country->country_short_name ='KN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Saint LUCIA';
        $country->country_short_name ='LC';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Saint Vincent';
        $country->country_short_name ='VC';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Samoa';
        $country->country_short_name ='WS';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'San Marino';
        $country->country_short_name ='SM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Sao Tome And Principe';
        $country->country_short_name ='ST';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Saudi Arabia';
        $country->country_short_name ='SA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Senegal';
        $country->country_short_name ='SN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Seychelles';
        $country->country_short_name ='SC';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Sierra Leone';
        $country->country_short_name ='SL';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Singapore';
        $country->country_short_name ='SG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Slovakia';
        $country->country_short_name ='SK';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Slovenia';
        $country->country_short_name ='SI';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Solomon Islands';
        $country->country_short_name ='SB';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Somalia';
        $country->country_short_name ='SO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'South Africa';
        $country->country_short_name ='ZA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'South Georgia And The South Sandwich Islands';
        $country->country_short_name ='GS';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Spain';
        $country->country_short_name ='ES';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Sri Lanka';
        $country->country_short_name ='LK';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'St. Helena';
        $country->country_short_name ='SH';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'St. Vincent & Grenadines';
        $country->country_short_name ='PM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Sudan';
        $country->country_short_name ='SD';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Suriname';
        $country->country_short_name ='SR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Svalbard and Jan Mayen Islands';
        $country->country_short_name ='SJ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Swaziland';
        $country->country_short_name ='SZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Sweden';
        $country->country_short_name ='SE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Switzerland';
        $country->country_short_name ='CH';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Syria';
        $country->country_short_name ='SY';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Taiwan';
        $country->country_short_name ='TW';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Tajikistan';
        $country->country_short_name ='TJ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Tanzania';
        $country->country_short_name ='TZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Thailand';
        $country->country_short_name ='TH';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Togo';
        $country->country_short_name ='TG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Tokelau';
        $country->country_short_name ='TK';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Tonga';
        $country->country_short_name ='TO';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Trinidad And Tobago';
        $country->country_short_name ='TT';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Tunisia';
        $country->country_short_name ='TN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Turkey';
        $country->country_short_name ='TR';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Turkmenistan';
        $country->country_short_name ='TM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Turks And Caicos Islands';
        $country->country_short_name ='TC';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Tuvalu';
        $country->country_short_name ='TV';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Uganda';
        $country->country_short_name ='UG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Ukraine';
        $country->country_short_name ='UA';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'United Arab Emirates';
        $country->country_short_name ='AE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'United Kingdom';
        $country->country_short_name ='GB';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'United States';
        $country->country_short_name ='US';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Uruguay';
        $country->country_short_name ='UY';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Uzbekistan';
        $country->country_short_name ='UZ';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Vanuatu';
        $country->country_short_name ='VU';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Venezuela';
        $country->country_short_name ='VE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Vietnam';
        $country->country_short_name ='VN';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Virgin Islands (British)';
        $country->country_short_name ='VG';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Virgin Islands';
        $country->country_short_name ='VI';
        $country->save();

        /***/
        $country = new countryModel;
        $country->country_long_name = 'Wallis And Futuna';
        $country->country_short_name ='WF';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Western Sahara';
        $country->country_short_name ='EH';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Yemen';
        $country->country_short_name ='YE';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Zambia';
        $country->country_short_name ='ZM';
        $country->save();
        /***/
        $country = new countryModel;
        $country->country_long_name = 'Zimbabwe';
        $country->country_short_name ='ZW';
        $country->save();
    }
}
