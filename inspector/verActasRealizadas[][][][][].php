/*
 * Copyright (C) 2006 The Android Open Source Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package com.android.internal.telephony;

/**
 * {@hide}
 */
public class ATResponseParser
{
    /*************************** Instance Variables **************************/

    private String mLine;
    private int mNext = 0;
    private int mTokStart, mTokEnd;

    /***************************** Class Methods *****************************/

    public
    ATResponseParser (String line)
    {
        mLine = line;
    }

    public boolean
    nextBoolean()
    {
        // "\s*(\d)(,|$)"
        // \d is '0' or '1'

        nextTok();

        if (mTokEnd - mTokStart > 1) {
            throw new ATParseEx();
        }
        char c = mLine.charAt(mTokStart);

        if (c == '0') return false;
        if (c ==  '1') return true;
        throw new ATParseEx();
    }


    /** positive int only */
    public int
    nextInt()
    {
        // "\s*(\d+)(,|$)"
        int ret = 0;

        nextTok();

        for (int i = mTokStart ; i < mTokEnd ; i++) {
            char c = mLine.charAt(i);

            // Yes, ASCII decimal digits only
            if (c < '0' || c > '9') {
                throw new ATParseEx();
            }

            ret *= 10;
            ret += c - '0';
        }

        return ret;
    }

    public String
    nextString()
    {
        nextTok(