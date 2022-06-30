<?php
return [
    'manufacturers' => [
        'Rapnet' => 'rapnet',
        'Jbbrothers' => 'jbbrothers',
        'MID_INV' => 'mid',
        'Kiran' => 'kiran',
        'SheetalStock' => 'sheetalstock'
    ],
    'options_titles' => [
        'rapnet' => [
            'shape' => 'Shape',
            'carat' => 'Weight',
            'color' => 'Color',
            'clarity' => 'Clarity',
            'cut' => 'Cut',
            'polish' => 'Polish',
            'symmetry' => 'Symmetry',
            'fluorescence' => 'Fluorescence Intensity',
            'dimensions' => 'Measurements',
            'girdle' => 'Girdle',
            'culet' => 'Culet',
            'depth' => 'Depth',
            'table' => 'Table',
            'stock_number' => 'Stock Number',
            'certificate_number' => 'Certificate Number'
        ],
        'jbbrothers' => [
            'shape' => 'Shape',
            'carat' => 'Carat',
            'color' => 'Color',
            'clarity' => 'Purity',
            'cut' => 'Cut',
            'polish' => 'Polish',
            'symmetry' => 'Symmetry',
            'fluorescence' => 'FL',
            'dimensions' => 'Measurements',
            'girdle' => 'GirdleCondition',
            'culet' => 'Culate',
            'depth' => 'TotalDepthPer',
            'table' => 'TabelPer',
            'stock_number' => 'RefNo',
            'certificate_number' => 'CertNo'
        ],
        'mid' => [
            'shape' => 'Shape',
            'carat' => 'Weight',
            'color' => 'Color',
            'clarity' => 'Clarity',
            'cut' => 'Cut Grade',
            'polish' => 'Polish',
            'symmetry' => 'Symmetry',
            'fluorescence' => 'Fluorescence Intensity',
            'dimensions' => 'Measurements',
            'girdle' => 'Girdle Thin', // клиет хочет с двух полей инфу Girdle Thin+Girdle Thick 
            'culet' => 'Culet Size', // в файле указано Culet Size (но в файле еще есть "Culet Condition"http://prntscr.com/m0yzeg), но оно не подходит под наши поля, возможно Питер опечатался 
            'depth' => 'Depth %',
            'table' => 'Table %',
            'stock_number' => 'Stock #',
            'certificate_number' => 'Certificate #'
        ],
        'kiran' => [
            'shape' => 'Shp',
            'carat' => 'Cts',
            'color' => 'Col',
            'clarity' => 'Clr',
            'cut' => 'Cut',
            'polish' => 'Pol',
            'symmetry' => 'Sym',
            'fluorescence' => 'Flr',
            'girdle' => 'Girdle',
            'culet' => 'Culet',
            'depth' => 'TD%',
            'table' => 'Tbl',
            'stock_number' => 'Stone No',
            'certificate_number' => 'Rep No',// в файле указано 1305300088  
            'length_width_ratio' => 'L:W'// в файле указано L:W

        ],
        'sheetalstock' => [
            'shape' => 'Shape',
            'carat' => 'Weight',
            'color' => 'Color',
            'clarity' => 'Clarity',
            'cut' => 'Cut grade',
            'polish' => 'Polish',
            'symmetry' => 'Symmetry',
            'fluorescence' => 'Fluorescence Intensity',
            'dimensions' => 'Measurements',
            'girdle' => 'Girdle Thin',  // в файле Girdle Thin+Girdle Thick
            'culet' => 'Culet Size',
            'depth' => 'Depth %',
            'table' => 'Table %',
            'stock_number' => 'Stock #',
            'certificate_number' => 'Certificate #'

        ]
    ],
    'options_values' => [
        'rapnet' => [
            'shape' => [
                'Round' => 'round',
                'Pear' => 'pear',
                'Heart' => 'heart',
                'Oval' => 'oval',
                'Princess' => 'princess',
                'Emerald' => 'emerald',
                'Asscher' => 'asscher',
                'Marquise' => 'marquise',
                'Cushion Brilliant' => 'cushion',
                'Cushion Modified' => 'cushion',
                'CUBR' => 'cushion',
                'Radiant' => 'radiant',
                'Square Radiant' => 'radiant'
            ],
            'clarity' => [
                'IF' => 'if',
                'I1' => 'i1',
                'I2' => 'i2',
                'SI1' => 'si1',
                'SI2' => 'si2',
                'SI3' => 'si3',
                'VS1' => 'vs1',
                'VS2' => 'vs2',
                'VVS1' => 'vvs1',
                'VVS2' => 'vvs2',
                'I2-I3' => 'i2i3',
                'FL' => 'fl',
                'NONE' => 'none'
            ],
            'color' => [
                'D' => 'd',
                'E' => 'e',
                'F' => 'f',
                'G' => 'g',
                'H' => 'h',
                'I' => 'i',
                'J' => 'j',
                'K' => 'k',
                'L' => 'l',
                'M' => 'm',
                'N' => 'n'
            ],
            'cut' => [
                'Excellent' => 'excellent',
                'Very Good' => 'very_good',
                'Good' => 'good',
                'Fair' => 'fair'
            ],
            'polish' => [
                'Excellent' => 'excellent',
                'Very Good' => 'very_good',
                'Good' => 'good'
            ],
            'symmetry' => [
                'Excellent' => 'excellent',
                'Very Good' => 'very_good',
                'Good' => 'good',
                'Fair' => 'fair'
            ],
            'fluorescence' => [
                'Medium' => 'medium',
                'Faint' => 'faint',
                'None' => 'none',
                'Strong' => 'strong',
                'Very Slight' => 'very_slight',
                'Slight' => 'slight',
                'Very Strong' => 'very_strong'
            ]
        ],
        'jbbrothers' => [
            'shape' => [
                'RD' => 'round',
                'PS' => 'pear',
                'HRT' => 'heart',
                'OV' => 'oval',
                'PR' => 'princess',
                'EM' => 'emerald',
                'SQEM' => 'asscher',
                'MQ' => 'marquise',
                'CU' => 'cushion',
                'CUBR' => 'cushion',
                'CUMBR' => 'cushion',
                'RT' => 'radiant',
                'SQRT' => 'radiant'
            ],
            'clarity' => [
                'IF' => 'if',
                'I1' => 'i1',
                'I2' => 'i2',
                'SI1' => 'si1',
                'SI2' => 'si2',
                'SI3' => 'si3',
                'VS1' => 'vs1',
                'VS2' => 'vs2',
                'VVS1' => 'vvs1',
                'VVS2' => 'vvs2',
                'I2-I3' => 'i2i3',
                'FL' => 'fl',
                'NONE' => 'none'
            ],
            'color' => [
                'D' => 'd',
                'E' => 'e',
                'F' => 'f',
                'G' => 'g',
                'H' => 'h',
                'I' => 'i',
                'J' => 'j',
                'K' => 'k',
                'L' => 'l',
                'M' => 'm'
            ],
            'cut' => [
                'EX+' => 'ideal',
                'EX' => 'excellent',
                'VG+' => 'very_good',
                'VG' => 'very_good',
                'GD+' => 'good',
                'GD' => 'good',
                'FR+' => 'fair'
            ],
            'polish' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'GD' => 'good'
            ],
            'symmetry' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'GD' => 'good'
            ],
            'fluorescence' => [
                'M' => 'medium',
                'F' => 'faint',
                'N' => 'none',
                'S' => 'strong',
                'VS' => 'very_strong'
            ],
            'culet' => [
                'PO' => 'pointed',
                'MD' => 'medium',
                'FC' => 'faceted',
                'BR' => 'broken'
            ]
        ],
        'mid' => [
            'shape' => [
                'Round' => 'round',
                'Pear' => 'pear',
                'Heart' => 'heart',
                'Oval' => 'oval',
                'Princess' => 'princess',
                'Emerald' => 'emerald',
                'Asscher' => 'asscher',
                'Marquise' => 'marquise',
                'Cushion' => 'cushion',
                'Radiant' => 'radiant'
            ],
            'clarity' => [
                'IF' => 'if',
                'I1' => 'i1',
                'I2' => 'i2',
                'SI1' => 'si1',
                'SI2' => 'si2',
                'SI3' => 'si3',
                'VS1' => 'vs1',
                'VS2' => 'vs2',
                'VVS1' => 'vvs1',
                'VVS2' => 'vvs2',
                'I2-I3' => 'i2i3',
                'FL' => 'fl',
                'NONE' => 'none'
            ],
            'color' => [
                'D' => 'd',
                'E' => 'e',
                'F' => 'f',
                'G' => 'g',
                'H' => 'h',
                'I' => 'i',
                'J' => 'j',
                'K' => 'k',
                'L' => 'l',
                'M' => 'm'
            ],
            'cut' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'G' => 'good',
                'F' => 'fair',
                'P' => 'poor',
                'NONE' => 'none'
            ],
            'polish' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'G' => 'good',
                'F' => 'fair',
                'P' => 'poor',
                'NONE' => 'none'
            ],
            'symmetry' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'G' => 'good',
                'F' => 'fair',
                'P' => 'poor',
                'NONE' => 'none'
            ],
            'fluorescence' => [
                'M' => 'medium',
                'F' => 'faint',
                'N' => 'none',
                'ST' => 'strong',
                'VST' => 'very_strong'
            ],
            'culet' => [
                'NONE' => 'none',
                'VERY SMALL' => 'very_small',
                'SMALL' => 'small',
                'MEDIUM' => 'medium',
                'LARGE' => 'large',
                'SLIGHTLY LARGE' => 'slightly_large',
                'VERY LARGE' => 'very_large'
            ]
        ],
        'kiran' => [
            'shape' => [
                'RB' => 'round',
                'PE' => 'pear',
                'HT' => 'heart',
                'OL' => 'oval',
                'PR' => 'princess',
                'EM' => 'emerald',
                'ASH' => 'asscher',
                'MQ' => 'marquise',
                'CU' => 'cushion',
                'RD' => 'radiant'
            ],
            'clarity' => [
                'IF' => 'if',
                'I1' => 'i1',
                'I2' => 'i2',
                'SI1' => 'si1',
                'SI2' => 'si2',
                'SI3' => 'si3',
                'VS1' => 'vs1',
                'VS2' => 'vs2',
                'VVS1' => 'vvs1',
                'VVS2' => 'vvs2',
                'I2-I3' => 'i2i3',
                'FL' => 'fl',
                'NONE' => 'none'
            ],
            'color' => [
                'D' => 'd',
                'E' => 'e',
                'F' => 'f',
                'G' => 'g',
                'H' => 'h',
                'I' => 'i',
                'J' => 'j',
                'K' => 'k',
                'L' => 'l',
                'M' => 'm',
                'N' => 'n'
            ],
            'cut' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'G' => 'good',
                'F' => 'fair',
                'P' => 'poor',
                'NONE' => 'none'
            ],
            'polish' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'G' => 'good',
                'F' => 'fair',
                'P' => 'poor',
                'NONE' => 'none'
            ],
            'symmetry' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'G' => 'good',
                'F' => 'fair',
                'P' => 'poor',
                'NONE' => 'none'
            ],
            'fluorescence' => [
                'Med' => 'medium',
                'Fnt' => 'faint',
                'None' => 'none',
                'Non' => 'none',
                'Stg' => 'strong',
                'Vst' => 'very_strong'
            ],
            'culet' => [
                'NON' => 'none',
                'None' => 'none',
                'Pointed' => 'pointed',
                'MED' => 'medium',
                'SM' => 'small',
                'VSM' => 'very_small'
            ]
        ],
        'sheetalstock' => [
            'shape' => [
                'Round' => 'round',
                'Pear' => 'pear',
                'Heart' => 'heart',
                'Oval' => 'oval',
                'Princess' => 'princess',
                'Emerald' => 'emerald',
                'Marquise' => 'marquise',
                'Cushion Modified' => 'cushion',
                'Radiant' => 'radiant'
            ],
            'clarity' => [
                'IF' => 'if',
                'I1' => 'i1',
                'I2' => 'i2',
                'SI1' => 'si1',
                'SI2' => 'si2',
                'SI3' => 'si3',
                'VS1' => 'vs1',
                'VS2' => 'vs2',
                'VVS1' => 'vvs1',
                'VVS2' => 'vvs2',
                'I2-I3' => 'i2i3',
                'FL' => 'fl',
                'NONE' => 'none'
            ],
            'color' => [
                'D' => 'd',
                'E' => 'e',
                'F' => 'f',
                'G' => 'g',
                'H' => 'h',
                'I' => 'i',
                'J' => 'j',
                'K' => 'k',
                'L' => 'l',
                'M' => 'm'
            ],
            'cut' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'G' => 'good',
                'F' => 'fair',
                'P' => 'poor',
                'NONE' => 'none'
            ],
            'polish' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'G' => 'good',
                'F' => 'fair',
                'P' => 'poor',
                'NONE' => 'none'
            ],
            'symmetry' => [
                'EX' => 'excellent',
                'VG' => 'very_good',
                'G' => 'good',
                'F' => 'fair',
                'P' => 'poor',
                'NONE' => 'none'
            ],
            'fluorescence' => [
                'M' => 'medium',
                'F' => 'faint',
                'N' => 'none',
                'S' => 'strong'
            ],
            'culet' => [
                'N' => 'none'
            ]
        ]
    ],
    //warning!! sync with import/config import.php
    'stock_number_prefix' => [
        'rapnet'         => 'H672',
        'jbbrothers'     => 'JH76',
        'mid'            => 'GSVX',
        'kiran'          => 'AN1E',
        'sheetalstock'   => 'FEWW',
        'san'            => '856K',
        'starraysreport' => 'BBB1',
        'zs'             => '10WE',
        'srk'            => 'LJ54',
        'dharamhk'       => 'U6GC',
        'laximi'         => 'EDLI',
    ],
];


/*

rapnet girdle:
'girdle' => [
    'Extr. Thick' => 'extremely_thick',
    'Very Thick' => 'very_thick',
    'Thick' => 'thick',
    'Slightly Thick' => 'slightly_thick',
    'Medium' => 'medium',
    'Thin' => 'thin',
    'Slightly Thin' => 'slightly_thin',
    'Very Thin' => 'very_thin',
    'Extr. Thin' => 'extremely_thin'
]


jbbrothers girdle:
'girdle' => [
    'x>=7' => 'extremely_thick',
    'x>=6,x<7' => 'very_thick',
    'x>=5,x<6' => 'thick',
    'x>=4,x<6' => 'slightly_thick',
    'x>=3,x<4' => 'medium',
    'x>=0.5,x<3' => 'thin',
    'x>=0.5,x<0' => 'very_thin',
    'x=0' => 'extremely_thin'
]

*/
