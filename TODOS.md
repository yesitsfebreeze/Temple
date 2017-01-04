[] creating all error codes for the exceptions  
[] need to write all tests ( except InjectionManager :) )
  
list of html functions i need to implement:  
    - modifier  
    - implement filter  
    - capture  
    
list of modifier functions i need to implement:
    - truncate
    

## languages

[] Variable cache interface to return getter string  
[] refactor language implementation

they should be only dependent on their own and should have one interface to connect them (plugin system)

functionality needed:
  - variable cache
  - plugins (they maybe need to be globalized)
  - modifier (they maybe need to be globalized)
  - nodes
  - hook for rendering (plugin?)
  - configs should be empty at first? 
  - configs need to be adjustable form the outside therefore they must be persistent for an instance
   
## documentation
- setup
- custom languages
- list of events and their use

