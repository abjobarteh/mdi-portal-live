<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create([
            'course_code' => 'ACC 211',
            'course_name' => 'Financial Accounting',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 213',
            'course_name' => 'Organizational Behaviour',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 212',
            'course_name' => 'Managerial Communication',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 214',
            'course_name' => 'Taxation 1',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 215',
            'course_name' => 'Auditing 1',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 221',
            'course_name' => 'Intermediate Financial Accounting I',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 222',
            'course_name' => 'Cost Accounting',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 223',
            'course_name' => 'Management Accounting I',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 224',
            'course_name' => 'Taxation II',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 225',
            'course_name' => 'Auditing II',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 311',
            'course_name' => 'International Finance',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 312',
            'course_name' => 'Professional Ethics',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 313',
            'course_name' => 'Accounting Theory and Practice',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 314',
            'course_name' => 'Intermediate Financial Accounting II',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 315',
            'course_name' => 'Management Accounting II',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 321',
            'course_name' => 'Strategic Managementt',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 322',
            'course_name' => 'International Finance',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 323',
            'course_name' => 'Investment Analysis',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 324',
            'course_name' => 'Accounting Information System',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 325',
            'course_name' => 'Project Writing I for Accountancy',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 411',
            'course_name' => 'Advanced Financial Accounting',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 412',
            'course_name' => 'Project Management',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 413',
            'course_name' => 'International Accounting System',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 414',
            'course_name' => 'Financial Planning and Capital Budgeting',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 415',
            'course_name' => 'Project Writing II for Accountancy',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 421',
            'course_name' => 'Internal Auditing and Operational Standards',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 422',
            'course_name' => 'Public Sector Accounting',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 423',
            'course_name' => 'Financial Statement Analysis',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 424',
            'course_name' => 'Business Law',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 425',
            'course_name' => 'Financial Management',
            'program_id' => 1
        ])->create([
            'course_code' => 'IM 101',
            'course_name' => 'Introduction to Management',
            'program_id' => 5
        ])->create([
            'course_code' => 'ICT 101',
            'course_name' => 'Introduction to ICT',
            'program_id' => 5
        ])->create([
            'course_code' => 'PLD 101',
            'course_name' => 'Programming Logic and Design',
            'program_id' => 5
        ])->create([
            'course_code' => 'IGC 101',
            'course_name' => 'Introduction to Gender Concepts',
            'program_id' => 5
        ])->create([
            'course_code' => 'ENG 101',
            'course_name' => 'English 1 (Basic Grammar)',
            'program_id' => 5
        ])->create([
            'course_code' => 'ICS 101',
            'course_name' => 'Introduction to Computer Science',
            'program_id' => 5
        ])->create([
            'course_code' => 'IHS 101',
            'course_name' => 'IT Hardware & Systems',
            'program_id' => 5
        ])->create([
            'course_code' => 'CS 101',
            'course_name' => 'Computer Security',
            'program_id' => 5
        ])->create([
            'course_code' => 'IBS 101',
            'course_name' => 'Introduction to Basic Statistics',
            'program_id' => 5
        ])->create([
            'course_code' => 'ENG 102',
            'course_name' => 'English 2 (Academic Writing)',
            'program_id' => 5
        ])->create([
            'course_code' => 'NI 101',
            'course_name' => 'Networking I',
            'program_id' => 5
        ])->create([
            'course_code' => 'DB 101',
            'course_name' => 'Database I',
            'program_id' => 5
        ])->create([
            'course_code' => 'WP 101',
            'course_name' => 'Web programming I',
            'program_id' => 5
        ])->create([
            'course_code' => 'CP 101',
            'course_name' => 'Computer programming I',
            'program_id' => 5
        ])->create([
            'course_code' => 'OS 101',
            'course_name' => 'Operating System',
            'program_id' => 5
        ])->create([
            'course_code' => 'NI 201',
            'course_name' => 'Networking II',
            'program_id' => 5
        ])->create([
            'course_code' => 'DB 201',
            'course_name' => 'Database II',
            'program_id' => 5
        ])->create([
            'course_code' => 'WP 201',
            'course_name' => 'Web programming II',
            'program_id' => 5
        ])->create([
            'course_code' => 'CP 201',
            'course_name' => 'Computer programming II',
            'program_id' => 5
        ])->create([
            'course_code' => 'JP 101',
            'course_name' => 'Junior Project',
            'program_id' => 5
        ])->create([
            'course_code' => 'GEN 211',
            'course_name' => 'Gender Mainstreaming ',
            'program_id' => 6
        ])->create([
            'course_code' => 'GEN 212',
            'course_name' => 'Gender & Development',
            'program_id' => 6
        ])->create([
            'course_code' => 'GEN 213',
            'course_name' => 'Ethics & Professional Behaviour',
            'program_id' => 6
        ])->create([
            'course_code' => 'GEN 214',
            'course_name' => 'Managerial Communication',
            'program_id' => 6
        ])->create([
            'course_code' => 'GEN 215',
            'course_name' => 'Research Methods',
            'program_id' => 6
        ])->create([
            'course_code' => 'GEN 221',
            'course_name' => 'Women in Politics',
            'program_id' => 6
        ])->create([
            'course_code' => 'GEN 222',
            'course_name' => 'Gender & Entrepreneurship',
            'program_id' => 6
        ])->create([
            'course_code' => 'GEN 223',
            'course_name' => 'Introduction to Community Development',
            'program_id' => 6
        ])->create([
            'course_code' => 'GEN 224',
            'course_name' => 'Gender & Leadership',
            'program_id' => 6
        ])->create([
            'course_code' => 'GEN 225',
            'course_name' => 'Gender & Human Resource Management',
            'program_id' => 6
        ])->create([
            'course_code' => 'IM',
            'course_name' => 'Introduction to Management',
            'program_id' => 2,
        ])->create([
            'course_code' => 'ELU1',
            'course_name' => 'English Language1(Grammar & Usage)',
            'program_id' => 2,
        ])->create([
            'course_code' => 'PE1',
            'course_name' => 'Principles of (Micro) Economics 2',
            'program_id' => 2,
        ])->create([
            'course_code' => 'STAT',
            'course_name' => 'Statistics',
            'program_id' => 2,
        ])->create([
            'course_code' => 'GENDER',
            'course_name' => 'Gender',
            'program_id' => 2,
        ])->create([
            'course_code' => 'IT',
            'course_name' => 'Introduction to Information Technology',
            'program_id' => 2,
        ])->create([
            'course_code' => 'ELU2',
            'course_name' => 'English 2 (Academic writing)',
            'program_id' => 2,
        ])->create([
            'course_code' => 'MKT',
            'course_name' => 'Marketing',
            'program_id' => 2,
        ])->create([
            'course_code' => 'ACC',
            'course_name' => 'Accounting',
            'program_id' => 2,
        ])->create([
            'course_code' => 'QM',
            'course_name' => 'Quantitative Methods',
            'program_id' => 2,
        ])->create([
            'course_code' => 'PE2',
            'course_name' => 'Principles of Macroeconomics',
            'program_id' => 2,
        ])->create([
            'course_code' => 'OB',
            'course_name' => 'Organizational Behaviour',
            'program_id' => 2,
        ])->create([
            'course_code' => 'MBF',
            'course_name' => 'Money, Banking & Finance',
            'program_id' => 2,
        ])->create([
            'course_code' => 'MC',
            'course_name' => 'Managerial Communication',
            'program_id' => 2,
        ])->create([
            'course_code' => 'BLCA',
            'course_name' => 'Bank Lending & Credit Administration',
            'program_id' => 2,
        ])->create([
            'course_code' => 'BLE',
            'course_name' => 'Banking Law & Ethics',
            'program_id' => 2,
        ])->create([
            'course_code' => 'FIPS',
            'course_name' => 'Financial Institutions: Practices & Service Delivery',
            'program_id' => 2,
        ])->create([
            'course_code' => 'FRAB',
            'course_name' => 'Financial Reporting & Analysis for banks',
            'program_id' => 2,
        ])->create([
            'course_code' => 'ME',
            'course_name' => 'Monetary Economics',
            'program_id' => 2,
        ])->create([
            'course_code' => 'CCS',
            'course_name' => 'Customer Care/Service',
            'program_id' => 2,
        ])->create([
            'course_code' => 'TM',
            'course_name' => 'Treasury Management',
            'program_id' => 2,
        ])->create([
            'course_code' => 'RM',
            'course_name' => 'Research Methods',
            'program_id' => 2,
        ])->create([
            'course_code' => 'PCI',
            'course_name' => 'Politics: Contemporary Issues',
            'program_id' => 2,
        ])->create([
            'course_code' => 'HRM',
            'course_name' => 'Human Resource Management',
            'program_id' => 3,
        ])->create([
            'course_code' => 'IA',
            'course_name' => 'Investment Analysis',
            'program_id' => 3,
        ])->create([
            'course_code' => 'AUD',
            'course_name' => 'Auditing',
            'program_id' => 3,
        ])->create([
            'course_code' => 'ED',
            'course_name' => 'Entrepreneurship Development',
            'program_id' => 3,
        ])->create([
            'course_code' => 'CFRM',
            'course_name' => 'Corporate Finance & Risk Mgt',
            'program_id' => 3,
        ])->create([
            'course_code' => 'POB',
            'course_name' => 'Practice of Banking',
            'program_id' => 3,
        ])->create([
            'course_code' => 'IF',
            'course_name' => 'International Finance',
            'program_id' => 3,
        ])->create([
            'course_code' => 'FM',
            'course_name' => 'Financial Management',
            'program_id' => 3,
        ])->create([
            'course_code' => 'ME',
            'course_name' => 'Managerial Economics',
            'program_id' => 3,
        ])->create([
            'course_code' => 'IM',
            'course_name' => 'Insurance Marketing',
            'program_id' => 3,
        ])->create([
            'course_code' => 'CT',
            'course_name' => 'Corporate Tax',
            'program_id' => 3,
        ])->create([
            'course_code' => 'PM',
            'course_name' => 'Project Management',
            'program_id' => 3,
        ])->create([
            'course_code' => 'MA',
            'course_name' => 'Management Accounting',
            'program_id' => 3,
        ])->create([
            'course_code' => 'SM',
            'course_name' => 'Strategic Management',
            'program_id' => 3,
        ])->create([
            'course_code' => 'RM',
            'course_name' => 'Research methods/ Project Writing',
            'program_id' => 3,
        ])->create([
            'course_code' => 'CMPT',
            'course_name' => 'Capital Markets & Portfolio Theory',
            'program_id' => 3,
        ])->create([
            'course_code' => 'PF',
            'course_name' => 'Public Finance',
            'program_id' => 3,
        ])->create([
            'course_code' => 'IBF',
            'course_name' => 'Islamic Banking & Finance',
            'program_id' => 3,
        ])->create([
            'course_code' => 'CB',
            'course_name' => 'Comparative Banking',
            'program_id' => 3,
        ])->create([
            'course_code' => 'TQM',
            'course_name' => 'Total Quality Management',
            'program_id' => 3,
        ])->create([
            'course_code' => 'IMGT',
            'course_name' => 'Intro-To Management',
            'program_id' => 7,
        ])->create([
            'course_code' => 'ISTAT',
            'course_name' => 'Intro. to Statistics',
            'program_id' => 7,
        ])->create([
            'course_code' => 'IIT',
            'course_name' => 'Intro. to IT',
            'program_id' => 7,
        ])->create([
            'course_code' => 'PME',
            'course_name' => 'Principles of Micro Economics',
            'program_id' => 7,
        ])->create([
            'course_code' => 'ENG1',
            'course_name' => 'English-1',
            'program_id' => 7,
        ])->create([
            'course_code' => 'BGC',
            'course_name' => 'Basic Gender Concepts',
            'program_id' => 7,
        ])->create([
            'course_code' => 'PPM',
            'course_name' => 'PPM',
            'program_id' => 7,
        ])->create([
            'course_code' => 'BA',
            'course_name' => 'Basic Acct',
            'program_id' => 7,
        ])->create([
            'course_code' => 'MKT',
            'course_name' => 'Marketing & Management',
            'program_id' => 7,
        ])->create([
            'course_code' => 'QM',
            'course_name' => 'Q M',
            'program_id' => 7,
        ])->create([
            'course_code' => 'PME2',
            'course_name' => 'Principle Of Macro Economics',
            'program_id' => 7,
        ])->create([
            'course_code' => 'ENG2',
            'course_name' => 'English 2',
            'program_id' => 7,
        ])->create([
            'course_code' => 'PM',
            'course_name' => 'Performance Management',
            'program_id' => 7,
        ])->create([
            'course_code' => 'OB',
            'course_name' => 'OB',
            'program_id' => 7,
        ])->create([
            'course_code' => 'IHRM',
            'course_name' => 'Intro-To HRM',
            'program_id' => 7,
        ])->create([
            'course_code' => 'MC',
            'course_name' => 'Managerial Communication',
            'program_id' => 7,
        ])->create([
            'course_code' => 'ETHICS',
            'course_name' => 'Ethics',
            'program_id' => 7,
        ])->create([
            'course_code' => 'RM',
            'course_name' => 'Research Methodology',
            'program_id' => 7,
        ])->create([
            'course_code' => 'IPA',
            'course_name' => 'Intro. To Public Admin',
            'program_id' => 7,
        ])->create([
            'course_code' => 'CG',
            'course_name' => 'Corporate Governance',
            'program_id' => 7,
        ])->create([
            'course_code' => 'IE',
            'course_name' => 'Intro. To Entrepr',
            'program_id' => 7,
        ])->create([
            'course_code' => 'PPM',
            'course_name' => 'Project Planning & Mgt',
            'program_id' => 7,
        ])->create([
            'course_code' => 'ALAW',
            'course_name' => 'Admin-Law',
            'program_id' => 7,
        ])->create([
            'course_code' => 'IMGT',
            'course_name' => 'Intro. To Mgt',
            'program_id' => 9,
        ])->create([
            'course_code' => 'ISTAT',
            'course_name' => 'Intro. To Statistics',
            'program_id' => 9,
        ])->create([
            'course_code' => 'IIT',
            'course_name' => 'Intro. To IT',
            'program_id' => 9,
        ])->create([
            'course_code' => 'PME',
            'course_name' => 'Principles of Micro Economics',
            'program_id' => 9,
        ])->create([
            'course_code' => 'ENG1',
            'course_name' => 'English-1',
            'program_id' => 9,
        ])->create([
            'course_code' => 'BGC',
            'course_name' => 'Basic Gender Concepts',
            'program_id' => 9,
        ])->create([
            'course_code' => 'PPM',
            'course_name' => 'PPM',
            'program_id' => 9,
        ])->create([
            'course_code' => 'MKT',
            'course_name' => 'Marketing',
            'program_id' => 9,
        ])->create([
            'course_code' => 'MGT',
            'course_name' => 'Mgt',
            'program_id' => 9,
        ])->create([
            'course_code' => 'ENG2',
            'course_name' => 'English-2',
            'program_id' => 9,
        ])->create([
            'course_code' => 'BA',
            'course_name' => 'Basic Accounting',
            'program_id' => 9,
        ])->create([
            'course_code' => 'PME2',
            'course_name' => 'Principles of Macro Economics',
            'program_id' => 9,
        ])->create([
            'course_code' => 'QM',
            'course_name' => 'QM',
            'program_id' => 9,
        ])->create([
            'course_code' => 'PM',
            'course_name' => 'Performance Mgt',
            'program_id' => 9,
        ])->create([
            'course_code' => 'OB',
            'course_name' => 'OB',
            'program_id' => 9,
        ])->create([
            'course_code' => 'ER',
            'course_name' => 'Employee Resourcing',
            'program_id' => 9,
        ])->create([
            'course_code' => 'HRM1',
            'course_name' => 'HRM Models',
            'program_id' => 9,
        ])->create([
            'course_code' => 'IHRM',
            'course_name' => 'Intro-To HRM',
            'program_id' => 9,
        ])->create([
            'course_code' => 'MC',
            'course_name' => 'Managerial Communication',
            'program_id' => 9,
        ])->create([
            'course_code' => 'OHS',
            'course_name' => 'Occupational Health & Safety',
            'program_id' => 9,
        ])->create([
            'course_code' => 'EFHRM',
            'course_name' => 'English For HRM',
            'program_id' => 9,
        ])->create([
            'course_code' => 'HRM2',
            'course_name' => 'HRM',
            'program_id' => 9,
        ])->create([
            'course_code' => 'SHRM',
            'course_name' => 'Strategic HRM',
            'program_id' => 9,
        ])->create([
            'course_code' => 'LAW',
            'course_name' => 'Labour Law',
            'program_id' => 9,
        ])->create([
            'course_code' => 'HRIS',
            'course_name' => 'HRIS',
            'program_id' => 9,
        ])->create([
            'course_code' => 'APR',
            'course_name' => 'Analysis of Performance Requirements',
            'program_id' => 9,
        ])->create([
            'course_code' => 'SMGT',
            'course_name' => 'Strategic MGT',
            'program_id' => 10,
        ])->create([
            'course_code' => 'IBM',
            'course_name' => 'International Business Mgt',
            'program_id' => 10,
        ])->create([
            'course_code' => 'SBI',
            'course_name' => 'Small Business Improvement',
            'program_id' => 10,
        ])->create([
            'course_code' => 'LM',
            'course_name' => 'Leadership & Mgt',
            'program_id' => 10,
        ])->create([
            'course_code' => 'PF&E',
            'course_name' => 'Public Finance & Economics',
            'program_id' => 10,
        ])->create([
            'course_code' => 'MIS',
            'course_name' => 'MIS',
            'program_id' => 10,
        ])->create([
            'course_code' => 'CMGT',
            'course_name' => 'Change Mgt',
            'program_id' => 10,
        ])->create([
            'course_code' => 'TAX',
            'course_name' => 'Taxation',
            'program_id' => 10,
        ])->create([
            'course_code' => 'PRTM',
            'course_name' => 'People Resource & Talent Mgt',
            'program_id' => 10,
        ])->create([
            'course_code' => 'OH&S',
            'course_name' => 'Occupational Health &Safety',
            'program_id' => 10,
        ])->create([
            'course_code' => 'MT',
            'course_name' => 'Mgt Theory',
            'program_id' => 10,
        ])->create([
            'course_code' => 'POL',
            'course_name' => 'Policy Analysis',
            'program_id' => 10,
        ])->create([
            'course_code' => 'AUD',
            'course_name' => 'Auditing',
            'program_id' => 10,
        ])->create([
            'course_code' => 'FM',
            'course_name' => 'Financial Management',
            'program_id' => 10,
        ])->create([
            'course_code' => 'P&M',
            'course_name' => 'Promotion & Marketing',
            'program_id' => 10,
        ])->create([
            'course_code' => 'COM',
            'course_name' => 'Communication',
            'program_id' => 10,
        ])->create([
            'course_code' => 'ED',
            'course_name' => 'Entrepreneurship Development',
            'program_id' => 10,
        ])->create([
            'course_code' => 'TQM',
            'course_name' => 'Total Quality Mgt',
            'program_id' => 10,
        ])->create([
            'course_code' => 'OO&M',
            'course_name' => 'Office Organization and Mgt',
            'program_id' => 10,
        ])->create([
            'course_code' => 'DA',
            'course_name' => 'Discourse Analysis',
            'program_id' => 10,
        ])->create([
            'course_code' => 'PD&M',
            'course_name' => 'Product Development & Mgt',
            'program_id' => 10,
        ])->create([
            'course_code' => 'ODD&C',
            'course_name' => 'Organisational Design, Development & Change',
            'program_id' => 10,
        ])->create([
            'course_code' => 'IMGT',
            'course_name' => 'InternationatMGT',
            'program_id' => 10,
        ])->create([
            'course_code' => 'BL',
            'course_name' => 'Business Law',
            'program_id' => 10,
        ])->create([
            'course_code' => 'PW',
            'course_name' => 'Project Writing',
            'program_id' => 10,
        ])->create([
            'course_code' => 'SFM',
            'course_name' => 'Strategic Financial Mgt',
            'program_id' => 12,
        ])->create([
            'course_code' => 'EDP',
            'course_name' => 'Economic Development Planning',
            'program_id' => 12,
        ])->create([
            'course_code' => 'DLG',
            'course_name' => 'Decentralization &Local Governance',
            'program_id' => 12,
        ])->create([
            'course_code' => 'RIC',
            'course_name' => 'Regional integration& Cooperation',
            'program_id' => 12,
        ])->create([
            'course_code' => 'PSC',
            'course_name' => 'Public Sector Communication',
            'program_id' => 12,
        ])->create([
            'course_code' => 'PSAF',
            'course_name' => 'Public Sector Acc. & Finance',
            'program_id' => 12,
        ])->create([
            'course_code' => 'PSMG',
            'course_name' => 'Public Sector Mgt & Governance',
            'program_id' => 12,
        ])->create([
            'course_code' => 'PSHRM',
            'course_name' => 'Public Sector HRM',
            'program_id' => 12,
        ])->create([
            'course_code' => 'PA',
            'course_name' => 'Policy Analysis',
            'program_id' => 12,
        ])->create([
            'course_code' => 'LO',
            'course_name' => 'Leadership in Org',
            'program_id' => 12,
        ])->create([
            'course_code' => 'SD',
            'course_name' => 'Sustainable Development',
            'program_id' => 12,
        ])->create([
            'course_code' => 'RMRM',
            'course_name' => 'Research Methods & Research Mgt',
            'program_id' => 12,
        ])->create([
            'course_code' => 'FRE',
            'course_name' => 'French',
            'program_id' => 12,
        ])->create([
            'course_code' => 'PSE',
            'course_name' => 'Public Sector Enterprise',
            'program_id' => 12,
        ])->create([
            'course_code' => 'RM',
            'course_name' => 'Risk Management In Banking & Insurance',
            'program_id' => 8,
        ])->create([
            'course_code' => 'MIS',
            'course_name' => 'Management Information systems',
            'program_id' => 8,
        ])->create([
            'course_code' => 'IBOI',
            'course_name' => 'Islamic Banking Operations & Insurance',
            'program_id' => 8,
        ])->create([
            'course_code' => 'BAM',
            'course_name' => 'Business Analytics for Managers',
            'program_id' => 8,
        ])->create([
            'course_code' => 'ABFA',
            'course_name' => 'Advance Business Finance & Analysis',
            'program_id' => 8,
        ])->create([
            'course_code' => 'FRE',
            'course_name' => 'French Language',
            'program_id' => 8,
        ])->create([
            'course_code' => 'ELL',
            'course_name' => 'Employment & Labour Law',
            'program_id' => 8,
        ])->create([
            'course_code' => 'APSE',
            'course_name' => 'Advanced Public Sector Economics',
            'program_id' => 8,
        ])->create([
            'course_code' => 'IVC',
            'course_name' => 'Islamic Venture capital & Financial Markets',
            'program_id' => 8,
        ])->create([
            'course_code' => 'APSE2',
            'course_name' => 'Advance Public Sector Economics',
            'program_id' => 8,
        ])->create([
            'course_code' => 'AFMIS',
            'course_name' => 'Advance Financial Markets, Institutions and Services',
            'program_id' => 8,
        ])->create([
            'course_code' => 'AIBTFM',
            'course_name' => 'Advance International Banking, Treasury & Forex Management',
            'program_id' => 8,
        ])->create([
            'course_code' => 'PGRM',
            'course_name' => 'Post Graduate Research Methods',
            'program_id' => 8,
        ])->create([
            'course_code' => 'THESIS',
            'course_name' => 'Thesis',
            'program_id' => 8,
        ])->create([
            'course_code' => 'MGT 101',
            'course_name' => 'Introduction to Management',
            'program_id' => 4
        ])->create([
            'course_code' => 'GEN 101',
            'course_name' => 'Introduction to Gender',
            'program_id' => 4
        ])->create([
            'course_code' => 'ECO 101',
            'course_name' => 'Microeconomics',
            'program_id' => 4
        ])->create([
            'course_code' => 'ICT 101',
            'course_name' => 'Introduction to Information Communication Technology (IT)',
            'program_id' => 4
        ])->create([
            'course_code' => 'ENG 101',
            'course_name' => 'English 101',
            'program_id' => 4
        ])->create([
            'course_code' => 'FRE 101',
            'course_name' => 'French 101',
            'program_id' => 4
        ])->create([
            'course_code' => 'INT 101',
            'course_name' => 'Introduction to Peace and Conflict',
            'program_id' => 4
        ])->create([
            'course_code' => 'PR 101',
            'course_name' => 'Introduction to Public Relations',
            'program_id' => 4
        ])->create([
            'course_code' => 'ECO 102',
            'course_name' => 'Macro Economics',
            'program_id' => 4
        ])->create([
            'course_code' => 'POL 101',
            'course_name' => 'Introduction to Politics',
            'program_id' => 4
        ])->create([
            'course_code' => 'ENG 102',
            'course_name' => 'English 102',
            'program_id' => 4
        ])->create([
            'course_code' => 'FRE 102',
            'course_name' => 'French 102',
            'program_id' => 4
        ])->create([
            'course_code' => 'INT 102',
            'course_name' => 'Introduction to International Relations',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIP 101',
            'course_name' => 'Introduction to Diplomacy',
            'program_id' => 4
        ])->create([
            'course_code' => 'IO 101',
            'course_name' => 'Introduction to International Organizations',
            'program_id' => 4
        ])->create([
            'course_code' => 'IRT 101',
            'course_name' => 'International Relations Theories',
            'program_id' => 4
        ])->create([
            'course_code' => 'FPA 101',
            'course_name' => 'Introduction to Foreign Policy Analysis',
            'program_id' => 4
        ])->create([
            'course_code' => 'RM 101',
            'course_name' => 'Research Methods',
            'program_id' => 4
        ])->create([
            'course_code' => 'ICM 101',
            'course_name' => 'International Conflict Management',
            'program_id' => 4
        ])->create([
            'course_code' => 'MD 101',
            'course_name' => 'Modern Diplomacy',
            'program_id' => 4
        ])->create([
            'course_code' => 'CSS 101',
            'course_name' => 'Contemporary Security Studies',
            'program_id' => 4
        ])->create([
            'course_code' => 'AI 101',
            'course_name' => 'Africa and the International System',
            'program_id' => 4
        ])->create([
            'course_code' => 'GFP 101',
            'course_name' => 'Gambia Foreign Policy',
            'program_id' => 4
        ])->create([
            'course_code' => 'IL 101',
            'course_name' => 'International Law',
            'program_id' => 4
        ])->create([
            'course_code' => 'RW 101',
            'course_name' => 'Research Work',
            'program_id' => 4
        ])->create([
            'course_code' => 'UNS 101',
            'course_name' => 'UN System',
            'program_id' => 13
        ])->create([
            'course_code' => 'DN 101',
            'course_name' => 'Diplomatic Negotiation',
            'program_id' => 13
        ])->create([
            'course_code' => 'LG 101',
            'course_name' => 'Leadership and Governance',
            'program_id' => 13
        ])->create([
            'course_code' => 'CIR 101',
            'course_name' => 'Contemporary Issues in International Relations',
            'program_id' => 13
        ])->create([
            'course_code' => 'IPE 101',
            'course_name' => 'International Political Economy',
            'program_id' => 13
        ])->create([
            'course_code' => 'ENIR 101',
            'course_name' => 'Ethics and Norms in International Relations',
            'program_id' => 13
        ])->create([
            'course_code' => 'CDN 101',
            'course_name' => 'Communication as Central Pillar in Diplomatic Negotiation',
            'program_id' => 13
        ])->create([
            'course_code' => 'RIR 101',
            'course_name' => 'Religion in International Relations',
            'program_id' => 13
        ])->create([
            'course_code' => 'DPG 101',
            'course_name' => 'Diplomatic Protocol and Protocol Guide',
            'program_id' => 13
        ])->create([
            'course_code' => 'GGIO 101',
            'course_name' => 'Global Governance and International Organization',
            'program_id' => 13
        ])->create([
            'course_code' => 'TIR 101',
            'course_name' => 'Theories of International Relations',
            'program_id' => 13
        ])->create([
            'course_code' => 'FPGP 101',
            'course_name' => 'Foreign Policy of Great Powers',
            'program_id' => 13
        ])->create([
            'course_code' => 'RNS 101',
            'course_name' => 'Rise of Nation States',
            'program_id' => 13
        ])->create([
            'course_code' => 'RGT 101',
            'course_name' => 'Regional and Global Terrorism',
            'program_id' => 13
        ])->create([
            'course_code' => 'AFS 101',
            'course_name' => 'African Studies',
            'program_id' => 13
        ])->create([
            'course_code' => 'CA 101',
            'course_name' => 'Conflict Analysis',
            'program_id' => 13
        ])->create([
            'course_code' => 'RC 101',
            'course_name' => 'Research Clinic',
            'program_id' => 13
        ])->create([
            'course_code' => 'SEM1 101',
            'course_name' => 'Seminar 1',
            'program_id' => 13
        ])->create([
            'course_code' => 'MIS 101',
            'course_name' => 'Management Information System (MIS)',
            'program_id' => 13
        ])->create([
            'course_code' => 'SEM2 101',
            'course_name' => 'Seminar 2',
            'program_id' => 13
        ])->create([
            'course_code' => 'RW2 101',
            'course_name' => 'Research work',
            'program_id' => 13
        ])->create([
            'course_code' => 'AFS 201',
            'course_name' => 'African Studies',
            'program_id' => 14
        ])->create([
            'course_code' => 'IDTP 201',
            'course_name' => 'Introduction to Diplomacy, Theories and Practice',
            'program_id' => 14
        ])->create([
            'course_code' => 'IHL 201',
            'course_name' => 'International Humanitarian Law',
            'program_id' => 14
        ])->create([
            'course_code' => 'FPGP 201',
            'course_name' => 'Foreign Policy of Great Powers',
            'program_id' => 14
        ])->create([
            'course_code' => 'TIR 201',
            'course_name' => 'Theories of International Relations',
            'program_id' => 14
        ])->create([
            'course_code' => 'HRGA 201',
            'course_name' => 'Human Rights in Global Affairs',
            'program_id' => 14
        ])->create([
            'course_code' => 'FREN 201',
            'course_name' => 'French',
            'program_id' => 14
        ])->create([
            'course_code' => 'PD 201',
            'course_name' => 'Public Diplomacy',
            'program_id' => 14
        ])->create([
            'course_code' => 'ICM 201',
            'course_name' => 'International Conflict Management',
            'program_id' => 14
        ])->create([
            'course_code' => 'THESIS 201',
            'course_name' => 'Thesis',
            'program_id' => 14
        ])->create([
            'course_code' => 'MGT 101',
            'course_name' => 'Introduction to Management',
            'program_id' => 15
        ])->create([
            'course_code' => 'GEN 101',
            'course_name' => 'Introduction to Gender',
            'program_id' => 15
        ])->create([
            'course_code' => 'ECO 101',
            'course_name' => 'Microeconomics',
            'program_id' => 15
        ])->create([
            'course_code' => 'ICT 101',
            'course_name' => 'Introduction to Information Communication Technology (IT)',
            'program_id' => 15
        ])->create([
            'course_code' => 'ENG 101',
            'course_name' => 'English 101',
            'program_id' => 15
        ])->create([
            'course_code' => 'FRE 101',
            'course_name' => 'French 101',
            'program_id' => 15
        ])->create([
            'course_code' => 'PC 101',
            'course_name' => 'Introduction to Peace and Conflict',
            'program_id' => 15
        ])->create([
            'course_code' => 'PR 101',
            'course_name' => 'Introduction to Public Relations',
            'program_id' => 15
        ])->create([
            'course_code' => 'ECO 102',
            'course_name' => 'Macro Economics',
            'program_id' => 15
        ])->create([
            'course_code' => 'POL 101',
            'course_name' => 'Introduction to Politics',
            'program_id' => 15
        ])->create([
            'course_code' => 'ENG 102',
            'course_name' => 'English 102',
            'program_id' => 15
        ])->create([
            'course_code' => 'FRE 102',
            'course_name' => 'French 102',
            'program_id' => 15
        ])->create([
            'course_code' => 'CA 101',
            'course_name' => 'Conflict Analysis',
            'program_id' => 15
        ])->create([
            'course_code' => 'EWCP 101',
            'course_name' => 'Early Warnings and Conflict Prevention',
            'program_id' => 15
        ])->create([
            'course_code' => 'IO 101',
            'course_name' => 'Introduction to International Organizations',
            'program_id' => 15
        ])->create([
            'course_code' => 'ID 101',
            'course_name' => 'Introduction to Diplomacy',
            'program_id' => 15
        ])->create([
            'course_code' => 'RM 101',
            'course_name' => 'Research Methods',
            'program_id' => 15
        ])->create([
            'course_code' => 'IR 101',
            'course_name' => 'Introduction to International Relations',
            'program_id' => 15
        ])->create([
            'course_code' => 'IHR 101',
            'course_name' => 'Introduction to Humanitarian and Refugee Studies',
            'program_id' => 15
        ])->create([
            'course_code' => 'PKO 101',
            'course_name' => 'Peacekeeping Operations',
            'program_id' => 15
        ])->create([
            'course_code' => 'CSS 101',
            'course_name' => 'Contemporary Security Studies',
            'program_id' => 15
        ])->create([
            'course_code' => 'ICM 101',
            'course_name' => 'International Conflict Management',
            'program_id' => 15
        ])->create([
            'course_code' => 'CRT 101',
            'course_name' => 'Conflict Resolution (Theory and Practice)',
            'program_id' => 15
        ])->create([
            'course_code' => 'RW 101',
            'course_name' => 'Research Work',
            'program_id' => 15
        ])->create([
            'course_code' => 'UNS 101',
            'course_name' => 'UN System',
            'program_id' => 16
        ])->create([
            'course_code' => 'PCR 101',
            'course_name' => 'Post Conflict Reconciliation',
            'program_id' => 16
        ])->create([
            'course_code' => 'ICM 102',
            'course_name' => 'International Conflict Management',
            'program_id' => 16
        ])->create([
            'course_code' => 'DLP 101',
            'course_name' => 'Diplomacy, Law and Protocol Engagement',
            'program_id' => 16
        ])->create([
            'course_code' => 'IHL 101',
            'course_name' => 'International Humanitarian Law and Human Rights',
            'program_id' => 16
        ])->create([
            'course_code' => 'CRS 101',
            'course_name' => 'Conflict Resolution Strategies',
            'program_id' => 16
        ])->create([
            'course_code' => 'CCF 101',
            'course_name' => 'Conflict Causative Factors and Theories',
            'program_id' => 16
        ])->create([
            'course_code' => 'GP 101',
            'course_name' => 'Gender and Peacekeeping',
            'program_id' => 16
        ])->create([
            'course_code' => 'RP 101',
            'course_name' => 'Religion in Peace and Conflict',
            'program_id' => 16
        ])->create([
            'course_code' => 'CAF 101',
            'course_name' => 'Conflict Analytical Frame',
            'program_id' => 16
        ])->create([
            'course_code' => 'IR 101',
            'course_name' => 'Introduction to International Relations',
            'program_id' => 16
        ])->create([
            'course_code' => 'RNS 101',
            'course_name' => 'Rise of Nation States',
            'program_id' => 16
        ])->create([
            'course_code' => 'AS 101',
            'course_name' => 'African Studies',
            'program_id' => 16
        ])->create([
            'course_code' => 'RGT 101',
            'course_name' => 'Regional and Global Terrorism',
            'program_id' => 16
        ])->create([
            'course_code' => 'DN 101',
            'course_name' => 'Diplomatic Negotiation',
            'program_id' => 16
        ])->create([
            'course_code' => 'RC 101',
            'course_name' => 'Research Clinic',
            'program_id' => 16
        ])->create([
            'course_code' => 'SEM 101',
            'course_name' => 'Seminar 1',
            'program_id' => 16
        ])->create([
            'course_code' => 'MIS 101',
            'course_name' => 'Management Information System (MIS)',
            'program_id' => 16
        ])->create([
            'course_code' => 'SEM 102',
            'course_name' => 'Seminar 2',
            'program_id' => 16
        ])->create([
            'course_code' => 'RW 101',
            'course_name' => 'Research Work',
            'program_id' => 16
        ]);
    }
}
