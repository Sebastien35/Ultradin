<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241124204619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country_iso3 (id_countryiso3 INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, iso3 VARCHAR(3) NOT NULL, currency VARCHAR(10) NOT NULL, PRIMARY KEY(id_countryiso3)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $countries = [
            ['country' => 'Afghanistan', 'iso3' => 'AFG', 'currency' => 'AFN'],
            ['country' => 'Albania', 'iso3' => 'ALB', 'currency' => 'ALL'],
            ['country' => 'Algeria', 'iso3' => 'DZA', 'currency' => 'DZD'],
            ['country' => 'Andorra', 'iso3' => 'AND', 'currency' => 'EUR'],
            ['country' => 'Angola', 'iso3' => 'AGO', 'currency' => 'AOA'],
            ['country' => 'Antigua and Barbuda', 'iso3' => 'ATG', 'currency' => 'XCD'],
            ['country' => 'Argentina', 'iso3' => 'ARG', 'currency' => 'ARS'],
            ['country' => 'Armenia', 'iso3' => 'ARM', 'currency' => 'AMD'],
            ['country' => 'Australia', 'iso3' => 'AUS', 'currency' => 'AUD'],
            ['country' => 'Austria', 'iso3' => 'AUT', 'currency' => 'EUR'],
            ['country' => 'Azerbaijan', 'iso3' => 'AZE', 'currency' => 'AZN'],
            ['country' => 'Bahamas', 'iso3' => 'BHS', 'currency' => 'BSD'],
            ['country' => 'Bahrain', 'iso3' => 'BHR', 'currency' => 'BHD'],
            ['country' => 'Bangladesh', 'iso3' => 'BGD', 'currency' => 'BDT'],
            ['country' => 'Barbados', 'iso3' => 'BRB', 'currency' => 'BBD'],
            ['country' => 'Belarus', 'iso3' => 'BLR', 'currency' => 'BYN'],
            ['country' => 'Belgium', 'iso3' => 'BEL', 'currency' => 'EUR'],
            ['country' => 'Belize', 'iso3' => 'BLZ', 'currency' => 'BZD'],
            ['country' => 'Benin', 'iso3' => 'BEN', 'currency' => 'XOF'],
            ['country' => 'Bhutan', 'iso3' => 'BTN', 'currency' => 'BTN'],
            ['country' => 'Bolivia', 'iso3' => 'BOL', 'currency' => 'BOB'],
            ['country' => 'Bosnia and Herzegovina', 'iso3' => 'BIH', 'currency' => 'BAM'],
            ['country' => 'Botswana', 'iso3' => 'BWA', 'currency' => 'BWP'],
            ['country' => 'Brazil', 'iso3' => 'BRA', 'currency' => 'BRL'],
            ['country' => 'Brunei', 'iso3' => 'BRN', 'currency' => 'BND'],
            ['country' => 'Bulgaria', 'iso3' => 'BGR', 'currency' => 'BGN'],
            ['country' => 'Burkina Faso', 'iso3' => 'BFA', 'currency' => 'XOF'],
            ['country' => 'Burundi', 'iso3' => 'BDI', 'currency' => 'BIF'],
            ['country' => 'Cambodia', 'iso3' => 'KHM', 'currency' => 'KHR'],
            ['country' => 'Cameroon', 'iso3' => 'CMR', 'currency' => 'XAF'],
            ['country' => 'Canada', 'iso3' => 'CAN', 'currency' => 'CAD'],
            ['country' => 'Cape Verde', 'iso3' => 'CPV', 'currency' => 'CVE'],
            ['country' => 'Central African Republic', 'iso3' => 'CAF', 'currency' => 'XAF'],
            ['country' => 'Chad', 'iso3' => 'TCD', 'currency' => 'XAF'],
            ['country' => 'Chile', 'iso3' => 'CHL', 'currency' => 'CLP'],
            ['country' => 'China', 'iso3' => 'CHN', 'currency' => 'CNY'],
            ['country' => 'Colombia', 'iso3' => 'COL', 'currency' => 'COP'],
            ['country' => 'Comoros', 'iso3' => 'COM', 'currency' => 'KMF'],
            ['country' => 'Congo (Brazzaville)', 'iso3' => 'COG', 'currency' => 'XAF'],
            ['country' => 'Congo (Kinshasa)', 'iso3' => 'COD', 'currency' => 'CDF'],
            ['country' => 'Costa Rica', 'iso3' => 'CRI', 'currency' => 'CRC'],
            ['country' => 'Croatia', 'iso3' => 'HRV', 'currency' => 'EUR'],
            ['country' => 'Cuba', 'iso3' => 'CUB', 'currency' => 'CUP'],
            ['country' => 'Cyprus', 'iso3' => 'CYP', 'currency' => 'EUR'],
            ['country' => 'Czech Republic', 'iso3' => 'CZE', 'currency' => 'CZK'],
            ['country' => 'Denmark', 'iso3' => 'DNK', 'currency' => 'DKK'],
            ['country' => 'Djibouti', 'iso3' => 'DJI', 'currency' => 'DJF'],
            ['country' => 'Dominica', 'iso3' => 'DMA', 'currency' => 'XCD'],
            ['country' => 'Dominican Republic', 'iso3' => 'DOM', 'currency' => 'DOP'],
            ['country' => 'Ecuador', 'iso3' => 'ECU', 'currency' => 'USD'],
            ['country' => 'Egypt', 'iso3' => 'EGY', 'currency' => 'EGP'],
            ['country' => 'El Salvador', 'iso3' => 'SLV', 'currency' => 'USD'],
            ['country' => 'Equatorial Guinea', 'iso3' => 'GNQ', 'currency' => 'XAF'],
            ['country' => 'Eritrea', 'iso3' => 'ERI', 'currency' => 'ERN'],
            ['country' => 'Estonia', 'iso3' => 'EST', 'currency' => 'EUR'],
            ['country' => 'Eswatini', 'iso3' => 'SWZ', 'currency' => 'SZL'],
            ['country' => 'Ethiopia', 'iso3' => 'ETH', 'currency' => 'ETB'],
            ['country' => 'Fiji', 'iso3' => 'FJI', 'currency' => 'FJD'],
            ['country' => 'Finland', 'iso3' => 'FIN', 'currency' => 'EUR'],
            ['country' => 'France', 'iso3' => 'FRA', 'currency' => 'EUR'],
            ['country' => 'Gabon', 'iso3' => 'GAB', 'currency' => 'XAF'],
            ['country' => 'Gambia', 'iso3' => 'GMB', 'currency' => 'GMD'],
            ['country' => 'Georgia', 'iso3' => 'GEO', 'currency' => 'GEL'],
            ['country' => 'Germany', 'iso3' => 'DEU', 'currency' => 'EUR'],
            ['country' => 'Ghana', 'iso3' => 'GHA', 'currency' => 'GHS'],
            ['country' => 'Greece', 'iso3' => 'GRC', 'currency' => 'EUR'],
            ['country' => 'Grenada', 'iso3' => 'GRD', 'currency' => 'XCD'],
            ['country' => 'Guatemala', 'iso3' => 'GTM', 'currency' => 'GTQ'],
            ['country' => 'Guinea', 'iso3' => 'GIN', 'currency' => 'GNF'],
            ['country' => 'Guinea-Bissau', 'iso3' => 'GNB', 'currency' => 'XOF'],
            ['country' => 'Guyana', 'iso3' => 'GUY', 'currency' => 'GYD'],
            ['country' => 'Haiti', 'iso3' => 'HTI', 'currency' => 'HTG'],
            ['country' => 'Honduras', 'iso3' => 'HND', 'currency' => 'HNL'],
            ['country' => 'Hungary', 'iso3' => 'HUN', 'currency' => 'HUF'],
            ['country' => 'Iceland', 'iso3' => 'ISL', 'currency' => 'ISK'],
            ['country' => 'India', 'iso3' => 'IND', 'currency' => 'INR'],
            ['country' => 'Indonesia', 'iso3' => 'IDN', 'currency' => 'IDR'],
            ['country' => 'Iran', 'iso3' => 'IRN', 'currency' => 'IRR'],
            ['country' => 'Iraq', 'iso3' => 'IRQ', 'currency' => 'IQD'],
            ['country' => 'Ireland', 'iso3' => 'IRL', 'currency' => 'EUR'],
            ['country' => 'Israel', 'iso3' => 'ISR', 'currency' => 'ILS'],
            ['country' => 'Italy', 'iso3' => 'ITA', 'currency' => 'EUR'],
            ['country' => 'Jamaica', 'iso3' => 'JAM', 'currency' => 'JMD'],
            ['country' => 'Japan', 'iso3' => 'JPN', 'currency' => 'JPY'],
            ['country' => 'Jordan', 'iso3' => 'JOR', 'currency' => 'JOD'],
            ['country' => 'Kazakhstan', 'iso3' => 'KAZ', 'currency' => 'KZT'],
            ['country' => 'Kenya', 'iso3' => 'KEN', 'currency' => 'KES'],
            ['country' => 'Kiribati', 'iso3' => 'KIR', 'currency' => 'AUD'],
            
        ];

        foreach ($countries as $country) {
            $this->addSql(sprintf(
                "INSERT INTO country_iso3 (country, iso3, currency) VALUES ('%s', '%s', '%s')",
                $country['country'],
                $country['iso3'],
                $country['currency']
            ));
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE country_iso3');
    }
}
