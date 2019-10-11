//
//  RegistrationModel.swift
//  Light Community
//
//  Created by Samuel Clauzon on 14/06/2019.
//  Copyright © 2019 Samuel Clauzon. All rights reserved.
//

import Foundation

class Registration {
    
    enum Sexes {
        case noSex
        case male
        case female
        case another
    }
    
    static var sexChoice: Sexes = .noSex
    
    static var isValidRegistration: Bool = false
    
}
