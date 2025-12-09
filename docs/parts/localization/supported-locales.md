
{{locale::each(Carbon::getAvailableMacroLocales())}} {{::endEach}}

|
Locale

 |

Language

 |

Diff syntax

 |

1-day diff

 |

2-days diff

 |

Month names

 |

Week days

 |

Units

 |

Short units

 |

Period

 |                         |
 | ----------------------- | --------------------------------------------------------------- | ---------------------------------------------------------------- | --------------------------------------------------------------------- | --------------------------------------------------------------------- | ------------------------------------------ | --- | -------------------------------------------------------------------------------------------- | --- | -------------------------------------------------------------------------------------------------------- |
 | {{eval(echo $locale;)}} | {{eval(echo (new \\Carbon\\Language($locale))->getIsoName();)}} | {{eval(echo Carbon::localeHasDiffSyntax($locale) ? '✅' : '❌';)}} | {{eval(echo Carbon::localeHasDiffOneDayWords($locale) ? '✅' : '❌';)}} | {{eval(echo Carbon::localeHasDiffTwoDayWords($locale) ? '✅' : '❌';)}} | {{eval(echo substr($locale, 0, 2) === 'en' |     | Carbon::parse('january')->monthName !== Carbon::parse('january')->locale($locale)->monthName |     | Carbon::parse('march')->monthName !== Carbon::parse('march')->locale($locale)->monthName ? '✅' : '❌';)}} | {{eval(echo substr($locale, 0, 2) === 'en' |  | Carbon::parse('monday')->dayName !== Carbon::parse('monday')->locale($locale)->dayName |  | Carbon::parse('sunday')->dayName !== Carbon::parse('sunday')->locale($locale)->dayName ? '✅' : '❌';)}} | {{eval(echo substr($locale, 0, 2) === 'en' |  | Carbon::now()->translate('month') !== Carbon::now()->locale($locale)->translate('month') |  | Carbon::now()->translate('day') !== Carbon::now()->locale($locale)->translate('day') ? '✅' : '❌';)}} | {{eval(echo substr($locale, 0, 2) === 'en' |  | Carbon::now()->translate('m') !== Carbon::now()->locale($locale)->translate('m') |  | Carbon::now()->translate('d') !== Carbon::now()->locale($locale)->translate('d') ? '✅' : '❌';)}} | {{eval(echo Carbon::localeHasPeriodSyntax($locale) ? '✅' : '❌';)}} |
