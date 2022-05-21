Expression compiler calculator
--- 

Research/educational project of the simple compiler for arithmetic expressions.
1. Study compiler theory.
2. Study different programming languages.
3. Just for fun.

## Grammar

```
expression ::= :term
             | :expression :operator :expression
             | (expression)
               
operator ::= +
           | -
           | *
           | /
           | // # integer division
           | %  # modulo division
           | ** # exponental
           | ^  # exponental
                
term ::= :number
       | :identifier
       | :identifier(:expression[,:expression])
 
number ::= /[0-9]+/
         | /[0-9]+\.[0-9]+/
 
identifier ::= /[a-zA-Z_][a-zA-Z0-9_]*/
```



example
```
-2 + (-2 // 4) 
+ (-(-16 % 10)) 
+ (2.55+1.150) * 10 
- (1 / 5 + 0.8 - 16 ^ 0.5) ^ 2.0 
- sin(pi/2) + cos(sin(0.0/210)) 
+ atan2(0**2, 1)
```