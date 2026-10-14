function getScoreFromRanges(value, ranges, reverse = false) {
    for (let range of ranges) {
        if (reverse) {
            if (value <= range.max && value >= range.min) {
                return range.score;
            }
        } else {
            if (value >= range.min && value <= range.max) {
                return range.score;
            }
        }
    }
    return 0;
}
const scoreChart = {
    boys: {
        6: {
            hundred: [
                { min: 17.50, max: 100, score: 0 },
                { min: 16.50, max: 17.49, score: 1.5 },
                { min: 15.50, max: 16.49, score: 3 },
                { min: 14.50, max: 15.49, score: 4.5 },
                { min: 13.50, max: 14.49, score: 6 },
                { min: 12.50, max: 13.49, score: 8 },
                { min: 0.1, max: 12.49, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            eight: [
                { min: 4.00, max: 100, score: 0 },
                { min: 3.46, max: 3.59, score: 1.5 },
                { min: 3.31, max: 3.45, score: 3 },
                { min: 3.16, max: 3.30, score: 4.5 },
                { min: 3.00, max: 3.15, score: 6 },
                { min: 2.46, max: 2.59, score: 8 },
                { min: 0.1, max: 2.45, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            jump: [
                { min: 0, max: 0.99, score: 0 },
                { min: 1.00, max: 1.24, score: 1.5 },
                { min: 1.25, max: 1.49, score: 3 },
                { min: 1.50, max: 1.74, score: 4.5 },
                { min: 1.75, max: 1.99, score: 6 },
                { min: 2.00, max: 2.24, score: 8 },
                { min: 2.25, max: 100, score: 10 }
            ],

            shuttle: [
                { min: 20.01, max: 100, score: 0 },
                { min: 19.01, max: 20.00, score: 1.5 },
                { min: 18.01, max: 19.00, score: 3 },
                { min: 17.01, max: 18.00, score: 4.5 },
                { min: 16.01, max: 17.00, score: 6 },
                { min: 15.01, max: 16.00, score: 8 },
                { min: 0.1, max: 15.00, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            ball: [
                { min: 0, max: 2.49, score: 0 },
                { min: 2.50, max: 2.99, score: 1.5 },
                { min: 3.00, max: 3.49, score: 3 },
                { min: 3.50, max: 3.99, score: 4.5 },
                { min: 4.00, max: 4.49, score: 6 },
                { min: 4.50, max: 4.99, score: 8 },
                { min: 5.00, max: 100, score: 10 }
            ]
        },
        9: {

            hundred: [
                { min: 14.01, max: 100, score: 0 },
                { min: 13.60, max: 14.00, score: 1.5 },
                { min: 13.20, max: 13.59, score: 3 },
                { min: 12.80, max: 13.19, score: 4.5 },
                { min: 12.40, max: 12.79, score: 6 },
                { min: 12.00, max: 12.39, score: 8 },
                { min: 0.1, max: 11.99, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            eight: [
                { min: 3.26, max: 100, score: 0 },
                { min: 3.10, max: 3.25, score: 1.5 },
                { min: 2.55, max: 3.09, score: 3 },
                { min: 2.40, max: 2.54, score: 4.5 },
                { min: 2.25, max: 2.39, score: 6 },
                { min: 2.10, max: 2.24, score: 8 },
                { min: 0.1, max: 2.09, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            jump: [
                { min: 0, max: 2.04, score: 0 },
                { min: 2.05, max: 2.19, score: 1.5 },
                { min: 2.20, max: 2.34, score: 3 },
                { min: 2.35, max: 2.49, score: 4.5 },
                { min: 2.50, max: 2.64, score: 6 },
                { min: 2.65, max: 2.79, score: 8 },
                { min: 2.80, max: 100, score: 10 }
            ],

            shuttle: [
                { min: 16.01, max: 100, score: 0 },
                { min: 15.51, max: 16.00, score: 1.5 },
                { min: 15.01, max: 15.50, score: 3 },
                { min: 14.51, max: 15.00, score: 4.5 },
                { min: 14.01, max: 14.50, score: 6 },
                { min: 13.50, max: 14.00, score: 8 },
                { min: 0.1, max: 13.49, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            ball: [
                { min: 0, max: 3.99, score: 0 },
                { min: 4.00, max: 4.99, score: 1.5 },
                { min: 5.00, max: 5.99, score: 3 },
                { min: 6.00, max: 6.99, score: 4.5 },
                { min: 7.00, max: 7.49, score: 6 },
                { min: 7.50, max: 7.99, score: 8 },
                { min: 8.00, max: 100, score: 10 }
            ]
        },
        11: {

            hundred: [
                { min: 14.00, max: 100, score: 0 },
                { min: 13.06, max: 14.00, score: 1.5 },
                { min: 13.01, max: 13.05, score: 3 },
                { min: 12.06, max: 13.00, score: 4.5 },
                { min: 12.01, max: 12.05, score: 6 },
                { min: 11.79, max: 12.00, score: 8 },
                { min: 0.1, max: 11.78, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            eight: [
                { min: 3.25, max: 100, score: 0 },
                { min: 3.10, max: 3.25, score: 1.5 },
                { min: 2.55, max: 3.09, score: 3 },
                { min: 2.40, max: 2.54, score: 4.5 },
                { min: 2.25, max: 2.39, score: 6 },
                { min: 2.10, max: 2.24, score: 8 },
                { min: 0.1, max: 2.09, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            jump: [
                { min: 0, max: 1.99, score: 0 },
                { min: 2.00, max: 2.29, score: 1.5 },
                { min: 2.30, max: 2.49, score: 3 },
                { min: 2.50, max: 2.59, score: 4.5 },
                { min: 2.60, max: 2.69, score: 6 },
                { min: 2.70, max: 2.79, score: 8 },
                { min: 2.80, max: 100, score: 10 }
            ],

            shuttle: [
                { min: 16.00, max: 100, score: 0 },
                { min: 15.01, max: 16.00, score: 1.5 },
                { min: 14.51, max: 15.00, score: 3 },
                { min: 14.01, max: 14.59, score: 4.5 },
                { min: 13.51, max: 14.00, score: 6 },
                { min: 13.01, max: 13.50, score: 8 },
                { min: 0.1, max: 13.00, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            ball: [
                { min: 0, max: 3.99, score: 0 },
                { min: 4.00, max: 4.99, score: 1.5 },
                { min: 5.00, max: 5.99, score: 3 },
                { min: 6.00, max: 6.99, score: 4.5 },
                { min: 7.00, max: 7.49, score: 6 },
                { min: 7.50, max: 7.99, score: 8 },
                { min: 8.00, max: 100, score: 10 }
            ]
        }
    },

    girls: {
        6: {
            hundred: [
                { min: 19.50, max: 100, score: 0 },
                { min: 18.50, max: 19.49, score: 1.5 },
                { min: 17.50, max: 18.49, score: 3 },
                { min: 16.50, max: 17.49, score: 4.5 },
                { min: 15.50, max: 16.49, score: 6 },
                { min: 14.50, max: 15.49, score: 8 },
                { min: 0.1, max: 14.49, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            eight: [
                { min: 4.41, max: 100, score: 0 },
                { min: 4.21, max: 4.40, score: 1.5 },
                { min: 4.01, max: 4.20, score: 3 },
                { min: 3.41, max: 4.00, score: 4.5 },
                { min: 3.21, max: 3.40, score: 6 },
                { min: 3.01, max: 3.20, score: 8 },
                { min: 0.1, max: 3.00, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            jump: [
                { min: 0, max: 0.84, score: 0 },
                { min: 0.85, max: 1.02, score: 1.5 },
                { min: 1.03, max: 1.20, score: 3 },
                { min: 1.21, max: 1.38, score: 4.5 },
                { min: 1.39, max: 1.56, score: 6 },
                { min: 1.57, max: 1.74, score: 8 },
                { min: 1.75, max: 100, score: 10 }
            ],

            shuttle: [
                { min: 23.00, max: 100, score: 0 },
                { min: 22.00, max: 22.99, score: 1.5 },
                { min: 21.00, max: 21.99, score: 3 },
                { min: 20.00, max: 20.99, score: 4.5 },
                { min: 19.00, max: 19.99, score: 6 },
                { min: 18.01, max: 18.99, score: 8 },
                { min: 0.1, max: 18.00, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            ball: [
                { min: 0, max: 1.24, score: 0 },
                { min: 1.25, max: 1.69, score: 1.5 },
                { min: 1.70, max: 2.14, score: 3 },
                { min: 2.15, max: 2.59, score: 4.5 },
                { min: 2.60, max: 3.04, score: 6 },
                { min: 3.05, max: 3.50, score: 8 },
                { min: 3.51, max: 100, score: 10 }
            ]
        },
        9: {

            hundred: [
                { min: 17.50, max: 100, score: 0 },
                { min: 16.70, max: 17.49, score: 1.5 },
                { min: 15.90, max: 16.69, score: 3 },
                { min: 15.10, max: 15.89, score: 4.5 },
                { min: 14.30, max: 15.09, score: 6 },
                { min: 13.50, max: 14.29, score: 8 },
                { min: 0.1, max: 13.49, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            eight: [
                { min: 5.00, max: 100, score: 0 },
                { min: 4.30, max: 4.59, score: 1.5 },
                { min: 4.00, max: 4.29, score: 3 },
                { min: 3.30, max: 3.59, score: 4.5 },
                { min: 3.00, max: 3.29, score: 6 },
                { min: 2.31, max: 2.59, score: 8 },
                { min: 0.1, max: 2.30, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            jump: [
                { min: 0, max: 1.99, score: 0 },
                { min: 2.00, max: 2.09, score: 1.5 },
                { min: 2.10, max: 2.19, score: 3 },
                { min: 2.20, max: 2.29, score: 4.5 },
                { min: 2.30, max: 2.39, score: 6 },
                { min: 2.40, max: 2.49, score: 8 },
                { min: 2.50, max: 100, score: 10 }
            ],

            shuttle: [
                { min: 18.00, max: 100, score: 0 },
                { min: 17.51, max: 17.99, score: 1.5 },
                { min: 17.00, max: 17.50, score: 3 },
                { min: 16.50, max: 16.99, score: 4.5 },
                { min: 16.00, max: 16.49, score: 6 },
                { min: 15.50, max: 15.99, score: 8 },
                { min: 0.1, max: 15.49, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            ball: [
                { min: 0, max: 1.99, score: 0 },
                { min: 2.00, max: 2.99, score: 1.5 },
                { min: 3.00, max: 3.49, score: 3 },
                { min: 3.50, max: 3.99, score: 4.5 },
                { min: 4.00, max: 4.49, score: 6 },
                { min: 4.50, max: 4.99, score: 8 },
                { min: 5.00, max: 100, score: 10 }
            ]
        },
        11: {
            hundred: [
                { min: 17.50, max: 100, score: 0 },
                { min: 17.00, max: 17.49, score: 1.5 },
                { min: 16.50, max: 16.99, score: 3 },
                { min: 15.50, max: 16.49, score: 4.5 },
                { min: 14.50, max: 15.49, score: 6 },
                { min: 13.50, max: 14.49, score: 8 },
                { min: 0.1, max: 13.49, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            eight: [
                { min: 5.00, max: 100, score: 0 },
                { min: 4.30, max: 4.59, score: 1.5 },
                { min: 4.00, max: 4.29, score: 3 },
                { min: 3.30, max: 3.59, score: 4.5 },
                { min: 3.00, max: 3.29, score: 6 },
                { min: 2.30, max: 2.59, score: 8 },
                { min: 0.1, max: 2.29, score: 10 },
                { min: 0, max: 0, score: 0 }
            ],

            jump: [
                { min: 0, max: 1.29, score: 0 },
                { min: 1.30, max: 1.59, score: 1.5 },
                { min: 1.60, max: 1.89, score: 3 },
                { min: 1.90, max: 2.19, score: 4.5 },
                { min: 2.20, max: 2.39, score: 6 },
                { min: 2.40, max: 2.49, score: 8 },
                { min: 2.50, max: 100, score: 10 }
            ],

            shuttle: [
                { min: 18, max: 100, score: 0 },
                { min: 17.51, max: 17.99, score: 1.5 },
                { min: 17.00, max: 17.50, score: 3 },
                { min: 16.50, max: 16.99, score: 4.5 },
                { min: 16.00, max: 16.49, score: 6 },
                { min: 15.50, max: 15.99, score: 8 },
                { min: 0.1, max: 15.49, score: 10 },
                { min: 0, max: 0, score: 0 }

            ],

            ball: [
                { min: 0, max: 1.99, score: 0 },
                { min: 2.00, max: 2.99, score: 1.5 },
                { min: 3.00, max: 3.49, score: 3 },
                { min: 3.50, max: 3.99, score: 4.5 },
                { min: 4.00, max: 4.49, score: 6 },
                { min: 4.50, max: 4.99, score: 8 },
                { min: 5.00, max: 100, score: 10 }
            ]

        }
    }
};
