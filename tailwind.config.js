const plugin = require('tailwindcss/plugin');

module.exports = {
    purge: [
        './resources/views/**/*.php',
        './resources/js/**/*.vue'
    ],
    theme: {
        screens: {
            xs: '420px',
            xs_max: {'max': '419px'},
            sm: '640px',
            sm_max: {'max': '639px'},
            md: '768px',
            md_max: {'max': '767px'},
            lg: '1024px',
            lg_max: {'max': '1023px'},
            xl: '1280px',
            xl_max: {'max': '1279px'},
            '2xl': '1440px',
            '2xl_max': {'max': '1439px'},
        },
        extend: {
            fonts: {},
            colors: {
                'daalder-black': '#6b6969',
                'daalder-grey': '#999999',
                'daalder-light-grey': '#e9e9e9',
                'daalder-lighter-grey': '#f7f7f7',
                'daalder-teal': '#ccffff',
                'daalder-darker-blue': '#1a437b',
                'daalder-dark-blue': '#016cb5',
                'daalder-blue': '#00baff',
                'daalder-lighter-blue': '#f2fbfe',
                'daalder-indigo': '#252464',
                'placeholder': '#9FA6B2',
                error: "#f62b2b",
            },
            spacing: {
                '1px': '1px',
                '.5': '0.125rem',
                '22': '5.5rem',
            },
            minHeight: {
                '400': '400px',
                '500': '500px'
            },
            rotate: {
                'tilt': '-3deg'
            },
            zIndex: {
                '1': '1'
            },
            fontSize: {
                'price-lg': '3em',
                'price-md': '1.5em',
                'huge': '8em'
            },
            lineHeight: {
                20: '5rem'
            }
        }
    },
    variants: {
        // accessibility: ['important', 'responsive', 'focus'],
        // alignContent: ['important', 'responsive'],
        // alignItems: ['important', 'responsive'],
        // alignSelf: ['important', 'responsive'],
        // appearance: ['important', 'responsive'],
        // backgroundAttachment: ['important', 'responsive'],
        // backgroundColor: ['important', 'responsive', 'hover', 'focus'],
        // backgroundPosition: ['important', 'responsive'],
        // backgroundRepeat: ['important', 'responsive'],
        // backgroundSize: ['important', 'responsive'],
        // borderCollapse: ['important', 'responsive'],
        // borderColor: ['important', 'responsive', 'hover', 'focus'],
        // borderRadius: ['important', 'responsive'],
        // borderStyle: ['important', 'responsive'],
        // borderWidth: ['important', 'responsive'],
        // boxShadow: ['important', 'responsive', 'hover', 'focus'],
        // boxSizing: ['important', 'responsive'],
        // cursor: ['important', 'responsive'],
        // display: ['important', 'responsive'],
        // fill: ['important', 'responsive'],
        // flex: ['important', 'responsive'],
        // flexDirection: ['important', 'responsive'],
        // flexGrow: ['important', 'responsive'],
        // flexShrink: ['important', 'responsive'],
        // flexWrap: ['important', 'responsive'],
        // float: ['important', 'responsive'],
        // clear: ['important', 'responsive'],
        // fontFamily: ['important', 'responsive'],
        // fontSize: ['important', 'responsive'],
        // fontSmoothing: ['important', 'responsive'],
        // fontStyle: ['important', 'responsive'],
        // fontWeight: ['important', 'responsive', 'hover', 'focus'],
        // height: ['important', 'responsive'],
        // inset: ['important', 'responsive'],
        // justifyContent: ['important', 'responsive'],
        // letterSpacing: ['important', 'responsive'],
        // lineHeight: ['important', 'responsive'],
        // listStylePosition: ['important', 'responsive'],
        // listStyleType: ['important', 'responsive'],
        // margin: ['important', 'responsive'],
        // maxHeight: ['important', 'responsive'],
        // maxWidth: ['important', 'responsive'],
        // minHeight: ['important', 'responsive'],
        // minWidth: ['important', 'responsive'],
        // objectFit: ['important', 'responsive'],
        // objectPosition: ['important', 'responsive'],
        // opacity: ['important', 'responsive', 'hover', 'focus'],
        // order: ['important', 'responsive'],
        // outline: ['important', 'responsive', 'focus'],
        // overflow: ['important', 'responsive'],
        // padding: ['important', 'responsive'],
        // placeholderColor: ['important', 'responsive', 'focus'],
        // pointerEvents: ['important', 'responsive'],
        // position: ['important', 'responsive'],
        // resize: ['important', 'responsive'],
        // stroke: ['important', 'responsive'],
        // strokeWidth: ['important', 'responsive'],
        // tableLayout: ['important', 'responsive'],
        // textAlign: ['important', 'responsive'],
        // textColor: ['important', 'responsive', 'hover', 'focus'],
        // textDecoration: ['important', 'responsive', 'hover', 'focus'],
        // textTransform: ['important', 'responsive'],
        // userSelect: ['important', 'responsive'],
        // verticalAlign: ['important', 'responsive'],
        // visibility: ['important', 'responsive'],
        // whitespace: ['important', 'responsive'],
        // width: ['important', 'responsive'],
        // wordBreak: ['important', 'responsive'],
        // zIndex: ['important', 'responsive'],
        // gap: ['important', 'responsive'],
        // gridAutoFlow: ['important', 'responsive'],
        // gridTemplateColumns: ['important', 'responsive'],
        // gridColumn: ['important', 'responsive'],
        // gridColumnStart: ['important', 'responsive'],
        // gridColumnEnd: ['important', 'responsive'],
        // gridTemplateRows: ['important', 'responsive'],
        // gridRow: ['important', 'responsive'],
        // gridRowStart: ['important', 'responsive'],
        // gridRowEnd: ['important', 'responsive'],
        // transform: ['important', 'responsive'],
        // transformOrigin: ['important', 'responsive'],
        // scale: ['important', 'responsive', 'hover', 'focus'],
        // rotate: ['important', 'responsive', 'hover', 'focus'],
        // translate: ['important', 'responsive', 'hover', 'focus'],
        // skew: ['important', 'responsive', 'hover', 'focus'],
        // transitionProperty: ['important', 'responsive'],
        // transitionTimingFunction: ['important', 'responsive'],
        // transitionDuration: ['important', 'responsive'],
    },
    plugins: [
        require('@tailwindcss/ui'),
        plugin(function({addVariant}) {
            addVariant('important', ({container}) => {
                container.walkRules(rule => {
                    rule.selector = `.i-${rule.selector.slice(1)}`;
                    rule.walkDecls(decl => {
                        decl.important = true;
                    })
                })
            })
        })
    ]
};
