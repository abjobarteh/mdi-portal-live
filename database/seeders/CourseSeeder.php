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
            'course_code' => 'CPS 121',
            'course_name' => 'Introduction to Computer Science',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 122',
            'course_name' => 'IT Hardware & Systems',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 123',
            'course_name' => 'Computer Security',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 124',
            'course_name' => 'Programming Logic and Design',
            'program_id' => 5
        ])->create([
            'course_code' => 'ENG 100',
            'course_name' => 'English 6 (Academic Writing)',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 211',
            'course_name' => 'Networking I',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 212',
            'course_name' => 'Database I',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 213',
            'course_name' => 'Web programming I',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 214',
            'course_name' => 'Computer programming I',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 215',
            'course_name' => 'Operating System',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 221',
            'course_name' => 'Networking II',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 222',
            'course_name' => 'Database II',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 223',
            'course_name' => 'Web programming II',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 224',
            'course_name' => 'Computer programming II',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 225',
            'course_name' => 'Junior Project Writing I',
            'program_id' => 5
        ])->create([
            'course_code' => 'DIR 211',
            'course_name' => 'International Organisations',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 212',
            'course_name' => 'Introduction to International Relations',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 213',
            'course_name' => 'Introduction to Diplomacy',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 214',
            'course_name' => 'Foreign Language (French 1)',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 215',
            'course_name' => 'Introduction to Political Science',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 216',
            'course_name' => 'Introduction to Peace & Conflict Studies',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 217',
            'course_name' => 'Research Methods',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 221',
            'course_name' => 'Foreign Language (French 2)',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 222',
            'course_name' => 'Contemporary Issues in International Relations',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 223',
            'course_name' => 'Management Information Systems',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 224',
            'course_name' => 'Africa and the International Systems',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 225',
            'course_name' => 'International Law',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 226',
            'course_name' => 'Foreign Policy Analysis',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 311',
            'course_name' => 'Gambian Foreign Policy',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 312',
            'course_name' => 'Culture, Values and Conflicts in War',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 313',
            'course_name' => 'Introduction to Peace Studies',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 314',
            'course_name' => 'International Relations and Security',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 315',
            'course_name' => 'Research Methods',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 321',
            'course_name' => 'Diplomacy in Negotiations',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 322',
            'course_name' => 'Leadership & Governance',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 323',
            'course_name' => 'The UN System',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 324',
            'course_name' => 'International Political Economy',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 325',
            'course_name' => 'Project Work',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 411',
            'course_name' => 'Communication: A Central Pillar in Resolution',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 412',
            'course_name' => 'Political Islam',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 413',
            'course_name' => 'International Political Economy',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 414',
            'course_name' => 'Global Governance and International Organizations',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 415',
            'course_name' => 'Research Methods',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 421',
            'course_name' => 'Ethics & Norms in International Relations',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 422',
            'course_name' => 'Nationalism & Ethnicity in International Relations',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 423',
            'course_name' => 'National Politics & International Relations',
            'program_id' => 4
        ])->create([
            'course_code' => 'DIR 424',
            'course_name' => 'Building a Harmonious World of Sustained Peace and Common Prosperity: China’s Diplomacy',
            'program_id' => 4
        ])->create([
            'course_code' => 'CPS 425',
            'course_name' => 'Diplomacy by Deception (Seminar I)
            African Development: A Myth or Westernisation (Seminar II)y',
            'program_id' => 4
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
            'course_code' => 'RMBI',
            'course_name' => 'Risk Management In Banking & Insurance',
            'program_id' => 9,
        ])->create([
            'course_code' => 'MIS',
            'course_name' => 'Management Information Systems',
            'program_id' => 9,
        ])->create([
            'course_code' => 'IBOI',
            'course_name' => 'Islamic Banking Operations & Insurance',
            'program_id' => 9,
        ])->create([
            'course_code' => 'BAM',
            'course_name' => 'Business Analytics for Managers',
            'program_id' => 9,
        ])->create([
            'course_code' => 'ABFA',
            'course_name' => 'Advance Business Finance & Analysis',
            'program_id' => 9,
        ])->create([
            'course_code' => 'FL',
            'course_name' => 'French Language',
            'program_id' => 9,
        ])->create([
            'course_code' => 'ELL',
            'course_name' => 'Employment & Labour Law',
            'program_id' => 9,
        ])->create([
            'course_code' => 'APSE',
            'course_name' => 'Advanced Public Sector Economics',
            'program_id' => 9,
        ])->create([
            'course_code' => 'IVCFM',
            'course_name' => 'Islamic Venture capital & Financial Markets',
            'program_id' => 9,
        ])->create([
            'course_code' => 'AFPSE',
            'course_name' => 'Advance Public Sector Economics',
            'program_id' => 9,
        ])->create([
            'course_code' => 'AFMIS',
            'course_name' => 'Advance Financial Markets, Institutions and Services',
            'program_id' => 9,
        ])->create([
            'course_code' => 'AIOTFM',
            'course_name' => 'Advance International Banking, Treasury & Forex Management',
            'program_id' => 9,
        ])->create([
            'course_code' => 'PGRM',
            'course_name' => 'Post Graduate Research Methods',
            'program_id' => 9,
        ])->create([
            'course_code' => 'THESIS',
            'course_name' => 'Thesis',
            'program_id' => 9,
        ]);
    }
}
